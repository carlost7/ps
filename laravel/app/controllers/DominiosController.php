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
            $dom = null;
            if ($validator->passes())
            {

                  $dominio = Input::get('dominio');
                  $sld = substr($dominio, 0, strpos($dominio, '.'));
                  $tld = substr($dominio, strpos($dominio, '.') + 1);
                  if ($this->Dominio->comprobarDominio($tld, $sld))
                  {
                        $resultado = true;
                        $mensaje = "El dominio " . $dominio . " es correcto";
                  }
                  else
                  {
                        $resultado = false;
                        $mensaje = "El dominio ya esta siendo utilizado";
                        $dom = $this->Dominio->obtenerDominiosSimilares($tld, $sld);
                  }
            }
            else
            {
                  $resultado = false;
                  $mensaje = $validator->messages()->first('dominio');
            }

            $response = array('resultado' => $resultado, 'mensaje' => $mensaje, 'dominios' => $dom);

            return Response::json($response);
      }

      /*
       * Usando la api de Enom generar el costo del dominio
       */

      public static function getCostoDominio($tld)
      {

            return $costo_dominio = 14.00;
      }

      /*
       * Esta funcion obtendrá los datos del usuario anonimo para redirigirlo a la página de servicios
       */

      public function obtenerDominioRequerido()
      {
            if ($this->isPostRequest())
            {
                  $validator = $this->getValidatorComprobarNombreDominio();
                  if ($validator->fails())
                  {

                        return Redirect::back()->withErrors($validator->messages());
                  }
                  else
                  {

                        Session::put('dominio_pendiente', Input::get('dominio'));
                        if (Input::get('ajeno'))
                        {
                              Session::put('dominio_ajeno', true);
                        }
                        else
                        {

                              Session::put('dominio_ajeno', false);
                        }
                  }
            }
            $planes = $this->Plan->listarPlanes();
            return View::make('dominios.confirmar')->with(array('planes' => $planes));
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
        |--------------------------------------
       */

      public function confirmarDominio()
      {
            $dominio_pendiente = Session::get('dominio_pendiente');
            if (!isset($dominio_pendiente))
            {
                  Session::flash('error', 'No existe el dominio que se agregará');
                  return Redirect::back();
            }
            //1.- 
            $validator = $this->getValidatorConfirmUser();
            if ($validator->passes())
            {
                  //2.-
                  $nombre = Input::get('nombre');
                  $correo = Input::get('correo');
                  $password = Input::get('password');
                  $plan_id = Input::get('plan');
                  $tipo_pago = Input::get('tipo_pago');
                  $tiempo_servicio = Input::get('tiempo_servicio');
                  $moneda = Input::get('moneda');
                  $dominio_ajeno = Session::get('dominio_ajeno');
                  $dominio = Session::get('dominio_pendiente');
                  //3.-
                  DB::beginTransaction();

                  $usuario = $this->agregarUsuario($nombre, $password, $correo);
                  if (isset($usuario) && $usuario->id)
                  {
                        //4.-
                        $plan_model = $this->Plan->mostrarPlan($plan_id);
                        if ($this->Dominio->apartarDominio($usuario, $dominio, $dominio_ajeno, $plan_model))
                        {
                              //5.-
                              $preference = PagosController::generarPagoServiciosIniciales($usuario, $dominio, $plan_model->id, $tipo_pago, $tiempo_servicio, $moneda);
                              //6.-
                              if (isset($preference))
                              {
                                    $data = array('usuario' => $usuario->email,
                                          'password' => Input::get('password'),
                                    );
                                    Mail::queue('email.nuevousuario', $data, function($message) {
                                          $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                    });
                                    DB::commit();
                                    $link = $preference['response'][Config::get('payment.init_point')];

                                    return Redirect::away($link);
                              }
                              else
                              {
                                    Session::flash('error', 'No se pudo generar el pago del servicio');
                              }
                        }
                  }

                  DB::rollback();
            }
            return Redirect::back()->withInput()->withErrors($validator->messages());
      }

      public static function eliminarDominioPendiente($dominio_pendiente)
      {
            if (DominioRepositoryEloquent::eliminarDominioPendiente($dominio_pendiente->id))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      public static function agregarDominio($usuario)
      {
            $dominio_pendiente = $usuario->dominio_pendiente;
            $home = new HomeController();
            $arrPass = $home->getPassword();
            $password = $arrPass['password'];
            $dominiosRepository = new DominioRepositoryEloquent();
            if (!$dominio_pendiente->is_ajeno)
            {
                  if (!self::comprarDominio($dominio_pendiente->dominio))
                  {
                        if (UsuariosController::actualizarPagoInicialUsuario($usuario))
                        {
                              $data = array('dominio' => $dominio->dominio);

                              Mail::queue('email.error_compra_dominio', $data, function($message) use ($usuario) {
                                    $message->to($usuario->email, $usuario->nombre)->subject('Creado dominio en primer server');
                              });
                              
                              return false;
                              
                        }
                        
                  }
            }
            
            DB::beginTransaction();
            $dominio = $dominiosRepository->agregarDominio($usuario->id, $dominio_pendiente->dominio, true, $dominio_pendiente->plan_id, $dominio_pendiente->is_ajeno, $password);
            if (isset($dominio->id))
            {
                  $FtpRepository = new FtpsRepositoryEloquent();
                  $FtpRepository->set_attributes($dominio);
                  $user = explode('.', $dominio->dominio);
                  $username = $user[0];
                  $hostname = 'primerserver.com';
                  $home_dir = 'public_html/' . $dominio->dominio;
                  $ftp = $FtpRepository->agregarFtp($username, $hostname, $home_dir, $password, true);
                  if (isset($ftp) && $ftp->id)
                  {
                        $dominiosRepository->eliminarDominioPendiente($usuario->dominio_pendiente->id);

                        if (UsuariosController::activarUsuario($usuario))
                        {

                              $data = array('dominio' => $dominio->dominio,
                                    'usuario' => $usuario->email,
                                    'ftp_user' => $ftp->username,
                                    'ftp_pass' => $password);

                              Mail::queue('email.welcome', $data, function($message) use ($usuario) {
                                    $message->to($usuario->email, $usuario->nombre)->subject('Creado dominio en primer server');
                              });

                              DB::commit();
                              echo "Usuario creado con éxito";                              
                        }
                        else
                        {
                              DB::rollback();
                              echo "no se pudo crear el usuario";                              
                        }
                        
                  }
                  else
                  {
                        DB::rollback();                        
                        echo "no se pudo crear el ftp";
                  }
            }
            else
            {
                  DB::rollback();                  
                  echo "no hay dominio";
            }
      }

      

      public static function comprarDominio($dominio)
      {
            $dominioRepository = new DominioRepositoryEloquent();
            $sld = substr($dominio, 0, strpos($dominio, '.'));
            $tld = substr($dominio, strpos($dominio, '.') + 1);
            if ($dominioRepository->comprarDominio($sld, $tld,null))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      public function seleccionarNuevoDominio()
      {
            $dominio_anterior = Session::get('dominio_pendiente');
            return View::make('dominios.seleccionar_nuevo')->with(array('dominio_anterior' => $dominio_anterior));
      }

      public function comprarNuevoDominio()
      {
            if ($this->isPostRequest())
            {
                  $validator = $this->getValidatorComprobarNombreDominio();
                  if ($validator->passes())
                  {
                        $dominio = Input::get('dominio');
                        if (self::comprarDominio($dominio))
                        {
                              Session::flash('message', 'Dominio comprado con exito');
                              return Redirect::to('/');
                        }
                        else
                        {
                              Session::flash('error', 'No se pudo comprar el dominio');
                              return Redirect::to('dominio/seleccionar_nuevo');
                        }
                  }
                  else
                  {
                        return Redirect::back()->withError($validator->messages());
                  }
            }
      }

      /*
       * Funcion para agregar usuario al sistema
       */

      protected function agregarUsuario($nombre, $password, $correo)
      {
            return $this->Usuario->agregarUsuario($nombre, $password, $correo, false, false, true);
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
                        'password.required' => 'Escriba su contraseña anterior',
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.required' => 'Repita la contraseña',
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
                        'dominio.regex' => 'Escriba el nombre del dominio correctamente. debe ser de la forma: dominio-nuevo.com o con codigo de pais',
                        )
            );
      }

}
