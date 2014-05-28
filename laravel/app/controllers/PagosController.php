<?php

/**
 * Description of PagosController
 *
 * @author carlos
 */
use PagosRepository as Pagos;
use PlanRepository as Plan;
use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as FTP;
use Carbon\Carbon;

class PagosController extends BaseController {

      protected $Pagos;
      protected $Plan;
      protected $Usuario;
      protected $Dominio;
      protected $Ftp;

      public function __construct(Pagos $pagos, Plan $plan, Usuario $usuario, Dominio $dominio, FTP $ftp)
      {
            parent::__construct();
            $this->Pagos = $pagos;
            $this->Plan = $plan;
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
      }

      /*
       * Pagina inicial Para mostrar los pagos que ha hecho el usuario
       */

      public function mostrarPagos()
      {
            $pagos = $this->Pagos->obtenerPagosUsuario(Auth::user()->id);
            return View::make('pagos.index', array('pagos' => $pagos));
      }

      /*
       * Funcion para aceptar los pagos--> viene de mercado pago
       */

      public function aceptarPago()
      {

            return View::make('pagos.aceptado');
      }

      /*
       * Funcion para pagos cancelados por el usuario -> viene de mercado pago
       */

      public function cancelarPago()
      {
            return View::make('pagos.cancelado');
      }

      /*
       * Funcion para pagos pendientes --> viene de mercado pago
       */

      public function pagoPendiente()
      {
            return View::make('pagos.pendiente');
      }

      /*
       * Función para obtener las notificaciones de mercado pago
       */

      public function obtenerIPNMercadoPago()
      {

            $id = Input::get('id');
            if (isset($id))
            {
                  $response = $this->Pagos->recibirNotificacionPago($id);
                  Log::error('IPN Mercado'.print_r($response,true));
                  if (isset($response))
                  {
                        $no_orden = $response['collection']['external_reference'];
                        Log::error('IPN Mercado'.print_r($no_orden,true));
                        $usuario = $this->actualizarstatusPago($no_orden, $response['collection']['status']);
                        switch ($response['collection']['status'])
                        {
                              case 'approved':
                                    Log::error('IPN Mercado aprobado');
                                    if ($this->agregarDominioSistema($usuario))
                                    {
                                          return Redirect::to('usuario/login');
                                    }
                                    else
                                    {
                                          Mail::queue('email.errorcreaciondominio', null, function($message) use ($usuario) {
                                                $message->to($usuario->email, $usuario->username)->subject('Error al crear el dominio en primerserver.com');
                                          });
                                    }
                                    break;
                              case 'cancelled':
                                    Log::error('IPN Mercado cancelado');
                                          Mail::queue('email.compracancelada', null, function($message) use ($usuario) {
                                                $message->to($usuario->email, $usuario->username)->subject('Compra cancelada');
                                          });
                                    break;
                              default:
                                    Log::error("pagoscontroller.obtenerIPNMercadoPago: default: " . print_r($response, true));
                                    break;
                        }
                  }
                  else
                  {
                        Log::error("PagosController.ObtenerMercadoPago: no recibido el anuncio");
                  }
            }
            else
            {
                  Log::error("PagosController.ObtenerMercadoPago: no se obtuvo id del post");
            }
      }

      /*
       * Funcion para confirmar el registro en el sistema
       * 
       * get: Muestra la página de confirmación de dominio
       * 
       * post: 
       * Crea una preferencia de pago en mercado pago
       * Agregar el usuario al sistema
       * Agrega los pagos pendientes en la base de datos
       * Agrega los datos del domiino pendiente en la base de datos
       * Envia un correo al usuario con sus datos
       * Redirige al usuario a mercado pago para completar su transaccion
       * 
       *        
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
                              $numero_orden = $this->obtenerNumeroOrden();
                              $preference_data = array(
                                    "items" => $this->generarItems('nuevo_registro', $plan),
                                    "payer" => $this->generarPayer(),
                                    "back_urls" => $this->generarBackUrls('nuevo_registro'),
                                    "external_reference" => $numero_orden,
                              );
                              $preference = $this->Pagos->generarPreferenciaPago($preference_data);

                              DB::beginTransaction();
                              $usuario = $this->agregarUsuarioSistema();
                              if (isset($usuario) && $usuario != false)
                              {
                                    if ($this->agregarPagoInicialSistema($usuario, $numero_orden))
                                    {
                                          if ($this->apartardominio($usuario, Input::get('dominio'), $this->is_dominio_propio(), $plan))
                                          {
                                                $data = array('usuario' => $usuario->email,
                                                      'password' => Input::get('password'),
                                                );
                                                Mail::queue('email.nuevousuario', $data, function($message) {
                                                      $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                                });

                                                DB::commit();
                                                Log::error('PagosController : ' . print_r($preference, true));
                                                $link = $this->Pagos->generarLinkPago($preference);
                                                return Redirect::away($link);
                                          }
                                          else
                                          {
                                                Session::flash('error', 'Error al apartar el dominio');
                                          }
                                    }
                                    else
                                    {
                                          Session::flash('error', 'Error al generar el pago en la base');
                                    }
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
                        Session::flash('error', $mensaje);
                        return Redirect::back()->withInput();
                  }
            }
      }

      /*
       * Obtiene el costo total de los servicios y lo redirige a la pagina /ajax
       */

      public function getCostoTotal()
      {
            $servicio = $this->getCostoTotalPreferencia();
            $servicio['total'] = '$' . $servicio['total'];
            return Response::json($servicio);
      }

      /*
       * Obtiene el costo total para usar en la preferencia de mercado pago;
       */

      public function getCostoTotalPreferencia()
      {
            $plan = $this->Plan->mostrarPlan(Input::get('plan'));
            $servicio = $this->getCostoServicio($plan);
            if (!$this->is_dominio_propio())
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

      /*
       * Obtener numero de orden para los articulos
       */

      protected function obtenerNumeroOrden()
      {
            $valor = Input::get('correo');
            $no_orden = Crypt::encrypt($valor);
            $no_orden = 'nuevousuario_'.substr($no_orden, 0, 10);
            dd($no_orden);
            return $no_orden;
      }

      /*
       * Obtiene el costo del servicio a partir de los datos introducidos por el usuario
       */

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

      /*
       * Si vamos a comprar el dominio, selecciona el costo que tendra
       */

      protected function getCostoDominio()
      {
            $costo_dominio = Session::get('costo_dominio');
            return array('total' => $costo_dominio, 'descripcion' => 'Dominio: ' . $costo_dominio . ' Anual');
      }

      /*
       * Genera un array de items para la preferencia de pago
       */

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

      /*
       * Genera un array con los datos del pagador para la preferencia
       */

      protected function generarPayer()
      {
            return array(
                  "name" => Input::get('nombre'),
                  "email" => Input::get('email'),
            );
      }

      /*
       * Genera un array con las urls de retorno para la preferencia
       */

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

      /*
       * Agrega el usuario nuevo al sistema
       */

      protected function agregarUsuarioSistema()
      {
            $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false, false, true);
            if ($usuario->id != null)
            {
                  return $usuario;
            }
            else
            {
                  Session::flash('error', 'Error al agregar usuario');
                  return false;
            }
      }

      /*
       * Agrega los pagos pendientes del usuario a la base de datos
       */

      protected function agregarPagoInicialSistema($usuario, $numero_orden)
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
            if ($this->Pagos->generarPagoBase('Servicio', $usuario, $costo_servicio['total'], $costo_servicio['descripcion'], $inicio, $vencimiento, true, $numero_orden, 'inicio'))
            {
                  if (!$this->is_dominio_propio())
                  {
                        $costo_dominio = $this->getCostoDominio();
                        if (!$this->Pagos->generarPagoBase('Dominio', $usuario, $costo_dominio['total'], $costo_dominio['descripcion'], $inicio, $vencimiento, true, $numero_orden, 'inicio'))
                        {
                              Session::flash('error', 'Ocurrio un error al registrar el pago del domiminio en la base de datos');
                              return false;
                        }
                  }
                  return true;
            }
            else
            {
                  Session::flash('error', 'Ocurrio un error al registrar el pago del servicio en la base de datos');
                  return false;
            }
      }

      /*
       * Agregar los datos del dominio a una tabla de la bd, para usarlos cuando termine de pagar
       */

      protected function apartarDominio($usuario, $dominio, $is_propio, $plan)
      {
            if ($this->Dominio->apartarDominio($usuario, $dominio, $is_propio, $plan))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Agrega el dominio al sistema una vez que se pago el servicio
       * 
       * Agrega el FTP para el usuario y envia un correo con los datos al correo;
       */

      protected function actualizarStatusPago($numero_orden, $status)
      {
            $usuario = $this->Pagos->actualizarRegistroPagoExterno($numero_orden, $status);
            Log::error('IPN Mercado registro pago externo'.print_r($response,true));
            if ($usuario != false && $usuario->id)
            {
                  return $usuario;
            }
            else
            {
                  Log::error('PagosController.actualizarStatusPago: ' . $numero_orden . ' ' . $status);
                  return null;
            }
      }

      protected function agregarDominioSistema($usuario)
      {

            if ($usuario != false && $usuario->id)
            {
                  DB::beginTransaction();
                  $dominio_pendiente = $this->Dominio->obtenerDominioPendiente($usuario);
                  if ($dominio_pendiente != false && $dominio_pendiente->id)
                  {
                        $password = HomeController::obtenerPasswordDominio();
                        $dominio = $this->Dominio->agregarDominio($dominio_pendiente->dominio, $password, $usuario->id, $dominio_pendiente->plan->id);
                        if (isset($dominio->id))
                        {
                              $this->Ftp->set_attributes($dominio);
                              $user = explode('.', $dominio->dominio);
                              $username = $user[0];
                              $hostname = 'primerserver.com';
                              $home_dir = 'public_html/' . $dominio->dominio;
                              $ftp = $this->Ftp->agregarFtp($username, $hostname, $home_dir, $password, true);
                              if ($ftp->id)
                              {
                                    if ($this->actualizarUsuarioPagado($usuario, true, false))
                                    {
                                          Session::put('message', 'La cuenta esta lista para usarse');
                                          DB::commit();
                                          $data = array('dominio' => $dominio->dominio,
                                                'usuario' => $usuario->email,
                                                'ftp_user' => $ftp->username,
                                                'ftp_pass' => $password);

                                          Mail::queue('email.welcome', $data, function($message) use ($usuario) {
                                                $message->to($usuario->email, $usuario->nombre)->subject('Nuevo Dominio Existente');
                                          });

                                          return true;
                                    }
                                    else
                                    {
                                          Session::flash('error', 'Error al actualizar el usuario');
                                    }
                              }
                              else
                              {
                                    Session::flash('error', 'Error al agregar el FTP');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'Error al agregar el dominio al servidor');
                        }
                  }
                  else
                  {
                        Session::flash('Error al obtener el dominio pendiente');
                  }
            }

            return false;
      }

      /*
       * Checa en la session si el usuario introdujo un dominio propio
       */

      protected function is_dominio_propio()
      {
            $existente = Session::get('existente');
            if (!isset($existente))
            {
                  return false;
            }
            else
            {
                  return true;
            }
      }

      /*
       * Actualizar el usuario para registrar que el pago esta realizado
       */

      protected function actualizarUsuarioPagado($usuario, $is_activo, $is_deudor)
      {

            $usuario = $this->Usuario->editarUsuarioPagado($usuario->id, $is_activo, $is_deudor);
            if ($usuario != false && $usuario->id)
            {
                  return true;
            }
            else
            {
                  Session::flash('error', 'Error al actualizar el usuario');
                  return false;
            }
      }

      /*
       * Obtiene el validador para los datos de confirmacion del dominio y usuario
       */

      protected function getValidatorConfirmacion()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'required|min:4',
                        'password' => 'required|min:2',
                        'password_confirmation' => 'required|same:password',
                        'dominio' => 'required',
                        'correo' => 'required|email', //|unique:user,email
                        'plan' => 'required|exists:planes,id',
                        'aceptar' => 'required|accepted',
                        'tipo_pago' => 'required',
                        'tiempo_servicio' => 'required_if:tipo_pago,mensual',
            ));
      }

      /*
       * Obtiene el validador para comprobar el nombre del dominio
       */

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
