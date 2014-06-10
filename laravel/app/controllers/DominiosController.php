<?php

/**
 * Controlador que permite crear dominios
 *
 * @author carlos
 */
use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as Ftp;
use PlanRepository as Plan;

class DominiosController extends BaseController {

      protected $Usuario;
      protected $Dominio;
      protected $Ftp;
      protected $Plan;

      public function __construct(Usuario $usuario, Dominio $dominio, Ftp $ftp, Plan $plan)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
            $this->Plan = $plan;
      }

      /*
       * Pagina inicial de dominios (inicial de la aplicación
       */

      public function index()
      {
            return View::make('dominios.index');
      }

      /*
       * Comprobar si se puede agregar el dominio, se tiene que usar ajax
       * //Modificar acorde con el vendedor de hosts
       */

      public function comprobarDominio()
      {
            $validator = $this->getValidatorComprobarNombreDominio();
            if ($validator->passes())
            {
                  $dominio = Input::get('dominio');
                  if (filter_var(gethostbyname($dominio), FILTER_VALIDATE_IP))
                  {
                        $resultado = false;
                        $mensaje = "El dominio " . $dominio . " ya esta siendo utilizado";
                  }
                  else
                  {
                        $resultado = true;
                        $mensaje = "El dominio " . $dominio . " es correcto";
                  }
            }
            else
            {
                  $resultado = false;
                  $mensaje = $validator->messages()->first('dominio');
            }

            $response = array('resultado' => $resultado, 'mensaje' => $mensaje);

            return Response::json($response);
      }

      /*
        |-----------------------------------
        | Esta función permite agregar usuarios al sistema y agregar su nombre de dominio
        | cuando el usuario pague sus servicios, se agregará el dominio a su usuario;
        | Pasos:
        | 1.- Comprobar los datos, nombre de dominio, nombre de usuario, correo, etc.
        | 2.- Obtener los datos del formulario
        | 3.- Crear un usuario nuevo
        | 4.- Agregar el dominio requerido a dominio pendiente
        | 5.- Agregar el pago a la base de datos
        | 6.- Enviar al usuario a la página de mercado pago o servicios de cobro
        | 7.- Obtener del usuario el pago.
        |------------------------------------
       */

      public function confirmarDominio()
      {
            $dominio_pendiente = Session::get('dominio_pendiente');
            if (!isset($dominio_pendiente))
            {
                  Session::flash('error', 'No existe el dominio que se agregará');
                  return Redirect::back();
            }
            $validator = $this->getValidatorConfirmUser();
            if ($validator->passes())
            {
                  $nombre = Input::get('nombre');
                  $correo = Input::get('correo');
                  $password = Input::get('password');
                  $plan = Input::get('plan');
                  $tipo_pago = Input::get('tipo_pago');
                  $tiempo_servicio = Input::get('tiempo_servicio');
                  $moneda = 'MXN';

                  if (Session::get('dominio_existente') == 1)
                  {
                        $precio_dominio = 12.00;
                        $precio_dominio_moneda = PagosController::convertirMoneda($precio_dominio, 'USD', $moneda);
                  }

                  dd($precio_dominio_moneda);
            }
            return Redirect::back()->withErrors($validator->messages);
      }

      /*
       * Esta funcion obtendrá los datos del usuario anonimo para redirigirlo a la página de servicios
       */

      public function obtenerDominioRequerido()
      {
            $validator = $this->getValidatorComprobarNombreDominio();
            if ($validator->passes())
            {
                  Session::put('dominio_pendiente', Input::get('dominio'));
                  Session::put('dominio_existente', Input::get('existente'));
                  $planes = $this->Plan->listarPlanes();
                  return View::make('dominios.confirmar')->with(array('planes' => $planes));
            }
            return Redirect::back()->withErrors($validator->messages);
      }

      /*
       * Confirmar la compra del dominio;
       */

      public function confirmarDominio1()
      {
            if ($this->isPostRequest())
            {
                  DB::beginTransaction();
                  $validator = $this->getValidatorConfirmUser();

                  if ($validator->passes())
                  {
                        $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false, true, false);
                        if ($usuario->id != null)
                        {
                              $plan = $this->Plan->mostrarPlan(Input::get('plan'));
                              $dominio = $this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id);
                              if (isset($dominio->id))
                              {
                                    $this->Ftp->set_attributes($dominio);
                                    $user = explode('.', $dominio->dominio);
                                    $username = $user[0];
                                    $hostname = 'primerserver.com';
                                    $home_dir = 'public_html/' . $dominio->dominio;
                                    $ftp = $this->Ftp->agregarFtp($username, $hostname, $home_dir, Input::get('password'), true);
                                    if ($ftp->id)
                                    {
                                          Session::put('message', 'La cuenta esta lista para usarse');
                                          DB::commit();
                                          $data = array('dominio' => $dominio->dominio,
                                                'usuario' => $usuario->email,
                                                'password' => Input::get('password'),
                                                'ftp_user' => $ftp->username,
                                                'ftp_pass' => Input::get('password'));

                                          Mail::queue('email.welcome', $data, function($message) {
                                                $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                          });

                                          return Redirect::to('usuario/login');
                                    }
                                    else
                                    {
                                          Session::put('error', 'Error al agregar el FTP');
                                    }
                              }
                              else
                              {
                                    Session::flash('error', 'Error al agregar el dominio al servidor');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'Error al agregar usuario');
                        }
                  }
                  DB::rollback();
                  return Redirect::back()->withInput()->withErrors($validator->messages());
            }
      }

      protected function agregarUsuarioSistema()
      {
            DB::beginTransaction();
            $validator = $this->getValidatorConfirmUser();

            if ($validator->passes())
            {
                  $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false, true, false);
                  if ($usuario->id != null)
                  {
                        $plan = Plan::where('nombre', '=', Input::get('plan'))->first();
                        $dominio = $this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id);
                        if (isset($dominio->id))
                        {
                              $this->Ftp->set_attributes($dominio);
                              $user = explode('.', $dominio->dominio);
                              $username = $user[0];
                              $hostname = 'primerserver.com';
                              $home_dir = 'public_html/' . $dominio->dominio;
                              $ftp = $this->Ftp->agregarFtp($username, $hostname, $home_dir, Input::get('password'), true);
                              if ($ftp->id)
                              {
                                    Session::put('message', 'La cuenta esta lista para usarse');
                                    DB::commit();
                                    $data = array('dominio' => $dominio->dominio,
                                          'usuario' => $usuario->email,
                                          'password' => Input::get('password'),
                                          'ftp_user' => $ftp->username,
                                          'ftp_pass' => Input::get('password'));

                                    Mail::queue('email.welcome', $data, function($message) {
                                          $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                    });

                                    return Redirect::to('usuario/login');
                              }
                              else
                              {
                                    Session::put('error', 'Error al agregar el FTP');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'Error al agregar el dominio al servidor');
                        }
                  }
                  else
                  {
                        Session::flash('error', 'Error al agregar usuario');
                  }
            }
            DB::rollback();
            return Redirect::back()->withInput()->withErrors($validator->messages());
      }

      /*
       */

      public static function getCostoDominio($dominio)
      {

            return $costo_dominio = 12.00;
      }

      /*
       * 
       */

      protected function getValidatorConfirmUser()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'required|min:4',
                        'password' => 'required|min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
                        'password_confirmation' => 'required|same:password',
                        'correo' => 'required|email|unique:user,email',
                        'plan' => 'required|exists:planes,id',
                        'aceptar' => 'required|accepted'
                        ), array(
                        'old_password.required' => 'Escriba su contraseña anterior',
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      /*
       */

      protected function getValidatorComprobarNombreDominio()
      {
            return Validator::make(Input::all(), array(
                        'dominio' => array('required'),
                        'dominio' => array('regex:/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/'),
                        ), array(
                        'dominio.required' => 'Es necesario especificar un dominio',
                        'dominio.regex' => 'El dominio ',
                        )
            );
      }

}
