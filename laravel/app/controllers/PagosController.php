<?php

/**
 * Description of PagosController
 *
 * @author carlos
 */
use PagosRepository as Pagos;
use PlanRepository as Plan;
use UsuariosRepository as Usuario;
use Carbon\Carbon;

class PagosController extends BaseController
{

      protected $Pagos;
      protected $Plan;
      protected $Usuario;

      public function __construct(Pagos $pagos, Plan $plan, Usuario $usuario)
      {
            parent::__construct();
            $this->Pagos = $pagos;
            $this->Plan = $plan;
            $this->Usuario = $usuario;
      }

      
      /*
       * Pagina inicial
       */
      public function mostrarPagos(){
            $pagos = $this->Pagos->obtenerPagosUsuario(Auth::user()->id);
            return View::make('pagos.index',array('pagos'=>$pagos));
      }
      
      
      public function aceptarPago(){
            return View::make('pagos.aceptado');
      }
      
      public function cancelarPago(){
            return View::make('pagos.cancelado');
      }
      
      public function pagoPendiente(){
            return View::make('pagos.pendiente');
      }
      

      /*
       * Funcion para confirmar si el dominio es correcto
       * 
       * get: muestra la página de confirmación de dominio
       * post: Crea un nuevo usuario, obtiene si el dominio le pertenece al usuario o es nuestro,
       * crea una preferencia de pago, y manda al usuario al sistema de pagos
       */

      public function confirmarRegistro()
      {
            if ($this->isPostRequest())
            {
                  $validator = $this->getValidatorConfirmacion();
                  if ($validator->passes())
                  {
                        $plan = $this->Plan->mostrarPlan(Input::get('plan'));
                        if (isset($plan))
                        {
                              $preference_data = array(
                                    "items" => $this->generarItems('nuevo_registro', $plan),
                                    "payer" => $this->generarPayer(),
                                    "back_urls" => $this->generarBackUrls('nuevo_registro'),
                              );
                              $preference = $this->Pagos->generarPreferenciaPago($preference_data);

                              DB::beginTransaction();
                              $usuario = $this->agregarUsuarioSistema();
                              if(isset($usuario) && $usuario!= false)
                              if ($this->agregarPagoInicialSistema($usuario,$preference))
                              {
                                    DB::commit();
                                    $link = $this->Pagos->generarLinkPago($preference);
                                    return Redirect::away($link);
                              }
                              else
                              {
                                    Session::flash('error', 'Error al generar el pago en la base');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'No selecciono ningun plan');
                        }
                  }
                  return Redirect::back()->withInput()->withErrors($validator);
            }
            else
            {
                  $dominio = Input::get('dominio');
                  $validator = $this->getValidatorComprobarNombreDominio($dominio);
                  if ($validator->passes())
                  {
                        Session::put('posible_dominio', Input::get('dominio'));
                        Session::put('existente', Input::get('existente'));
                        Session::put('costo_dominio', '8.00');
                        $planes = $this->Plan->listarPlanes();
                        return View::make('dominios.confirmar', array('dominio' => Input::get('dominio'), 'planes' => $planes));
                  }
                  else
                  {
                        $resultado = false;
                        $mensaje = $validator->messages()->first('dominio');
                        return Redirect::back()->withErrors($validator)->withInput();
                  }
            }
      }

      public function getCostoTotal()
      {
            $servicio = $this->getCostoTotalPreferencia();
            $servicio['total'] = '$' . $servicio['total'];
            return Response::json($servicio);
      }
      
      public function getCostoTotalPreferencia()
      {
            $plan = $this->Plan->mostrarPlan(Input::get('plan'));
            $servicio = $this->getCostoServicio($plan);
            $existente = Session::get('existente');            
            if (!isset($existente))
            {
                  $dominio = $this->getCostoDominio();
            }
            if (isset($dominio))
            {
                  $servicio['total'] = $servicio['total'] + $dominio['total'];
                  $servicio['descripcion'] = $servicio['descripcion'] . ', ' . $dominio['descripcion'];
            }            
            return $servicio;
      }

      protected function getCostoServicio($plan)
      {
            $resultado = 0.00;            
            $descripcion = $plan->nombre;
            $tipo_pago = Input::get('tipo_pago');
            if ($tipo_pago === 'anual')
            {
                  $resultado += $plan->costo_anual;
                  $descripcion = $descripcion . ' Plan: ' . $plan->costo_anual . 'Anual';
            }
            else
            {
                  $resultado += $plan->costo_mensual * Input::get('tiempo_servicio');
                  $descripcion = $descripcion . ' Plan: ' . $plan->costo_mensual . ' * ' . Input::get('tiempo_servicio') . ' meses';
            }

            return array('total' => $resultado, 'descripcion' => $descripcion);
      }

      protected function getCostoDominio()
      {
            $costo_dominio = Session::get('costo_dominio');
            return array('total' => $costo_dominio, 'descripcion' => 'Dominio: ' . $costo_dominio . ' Anual');
      }

      protected function generarItems($tipo, $plan)
      {
            switch ($tipo)
            {
                  case 'nuevo_registro':
                        $costos = $this->getCostoTotalPreferencia();
                        
                        $items = array(
                              array(
                                    "title" => "Plan " . $plan->nombre,
                                    "quantity" => 1,
                                    "currency_id" => $plan->moneda,
                                    "unit_price" => $costos['total'],
                                    "description" => $costos['descripcion'],
                              ),
                        );

                        return $items;

                        break;
                  default:
                        return null;
            }
      }

      protected function generarPayer()
      {
            return array(
                  "name" => Input::get('nombre'),
                  "email" => Input::get('email'),
            );
      }

      protected function generarBackUrls($tipo)
      {
            switch ($tipo)
            {
                  case 'nuevo_registro':
                        return array(
                              "success" => URL::Route("pagos/pago_aceptado"),
                              "failure" => URL::Route("pagos/pago_cancelado"),
                              "pending" => URL::Route("pagos/pago_pendiente"),
                        );
                        break;
                  default:
                        return null;
            }
      }

      protected function agregarUsuarioSistema()
      {
            $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false,false,true);
            if ($usuario->id != null)
            {
                  $data = array('usuario' => $usuario->email,
                        'password' => Input::get('password'),
                  );
                  Mail::queue('email.nuevousuario', $data, function($message) {
                        $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                  });
                  return $usuario;
            }
            else
            {
                  Session::flash('error', 'Error al agregar usuario');
                  return false;
            }
      }

      protected function agregarPagoInicialSistema($usuario,$preference)
      {
            $plan = $this->Plan->mostrarPlan(Input::get('plan'));
            $costo_servicio = $this->getCostoServicio($plan);
            $inicio = Carbon::now();
            if (Input::get('tipo_pago') == 'anual')
            {
                  $vencimiento = Carbon::now()->addYear();
            }
            else
            {
                  $vencimiento = Carbon::now()->addMonths(Input::get('tiempo_servicio'));
            }
            if ($this->Pagos->generarPagoBase('Servicio', $usuario, $costo_servicio['total'], $costo_servicio['descripcion'], $inicio, $vencimiento, true, $preference['response']['id'], 'inicio'))
            {
                  $existente = Session::get('existente');
                  if (!isset($existente))
                  {
                        $costo_dominio = $this->getCostoDominio();
                        if (!$this->Pagos->generarPagoBase('Dominio', $usuario, $costo_dominio['total'], $costo_dominio['descripcion'], $inicio, $vencimiento, true, $preference['response']['id'], 'inicio'))
                        {
                              Session::flash('error','Ocurrio un error al registrar el pago del domiminio en la base de datos');
                              return false;
                        }
                  }
                  return true;
            }else{
                  Session::flash('error','Ocurrio un error al registrar el pago del servicio en la base de datos');
                  return false;
            }
      }

      protected function getValidatorConfirmacion()
      {
            return Validator::make(Input::all(), array(
                          'nombre' => 'required|min:4',
                          'password' => 'required|min:2',
                          'password_confirmation' => 'required|same:password',
                          'dominio' => 'required',
                          'correo' => 'required|email|unique:user,email',
                          'plan' => 'required|exists:planes,id',
                          'aceptar' => 'required|accepted',
                          'tipo_pago' => 'required',
                          'tiempo_servicio' => 'required_if:tipo_pago,mensual',
            ));
      }

      protected function getValidatorComprobarNombreDominio()
      {
            return Validator::make(Input::all(), array(
                          'dominio' => array('required'),
                          'dominio' => array('regex:/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/'),
                            ), array(
                          'dominio.required' => 'Es necesario especificar un dominio',
                          'dominio.regex' => 'El dominio tiene que ser de la forma [nombredominio].[com|pais].[pais]',
                            )
            );
      }
}
