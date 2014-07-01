<?php

/**
 * Controlador para manejar a los usuarios
 *
 * @author carlos
 */
use UsuariosRepository as UserRep;

class UsuariosController extends BaseController {

      protected $Usuario;

      /*
       * Constructor de la clase
       */

      public function __construct(UserRep $usuario)
      {
            parent::__construct();
            $this->Usuario = $usuario;
      }

      /*
       * Funcion para loggear al usuario a la aplicación
       */

      public function login()
      {
            if ($this->isPostRequest())
            {

                  //valida los datos del usuario
                  $validator = $this->getLoginValidator();

                  if ($validator->passes())
                  {

                        //obtiene las credenciales
                        $credentials = array('email' => Input::get('correo'),
                              'password' => Input::get('password'));

                        if (Auth::attempt($credentials))
                        {
                              //revisa si el usuario es administrador
                              if (Auth::user()->is_admin)
                              {
                                    //Envia al administrador al inicio
                                    return Redirect::to('admin/usuarios');
                              }
                              else
                              {
                                    //agrega el dominio del usuario y lo redirige a su página de inicio
                                    Session::put('dominio', Auth::user()->dominio);
                                    return View::make('usuarios.inicio');
                              }
                        }
                        else
                        {
                              Session::flash('error','Correo o Contraseña incorrecta');
                        }
                  }                  
                  return Redirect::back()->withInput()->withErrors($validator);
            }

            return View::make('usuarios.entrar');
      }

      /*
       * Página prinicipal del usuario
       */

      public function iniciar()
      {
            //envia al usuario a su página de inicio
            return View::make('usuarios.inicio');
      }

      /*
       * Funcion para modificar el password
       */

      public function cambiarPasswordUsuario()
      {

            //revisar si es post
            if ($this->isPostRequest())
            {
                  //revisa si la validacion es correcta
                  $validator = $this->getCambioPasswordValidator();
                  if ($validator->passes())
                  {
                        //si escribio el password y si el password es el del usuario
                        if (strlen(Input::get('old_password')) > 0 && !Hash::check(Auth::user()->password, Input::get('old_password')))
                        {
                              $usuario = Auth::user();
                              //modifica la constraseña del usuario
                              if ($this->Usuario->editarUsuario($usuario->id, null, Input::get('password'), null, null, null, null))
                              {
                                    Session::flash('message', 'Cambio de contraseña correcto');
                                    if (Auth::User()->is_admin)
                                    {
                                          //si es administrador redirige al administrador a la pagina inicial
                                          return Redirect::to('admin/usuarios');
                                    }
                                    else
                                    {
                                          //redirige al usuario al inicio
                                          return Redirect::to('usuario/inicio');
                                    }
                              }
                              else
                              {
                                    Session::flash('error', 'Error al cambiar la contraseña');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'El password anterior no coincide con los datos de la base');
                        }
                  }
                  return Redirect::back()->withErrors($validator)->withInput();
            }
            else
            {
                  return View::make('usuarios.cambiar_password');
            }
      }

      /*
       * Funcion para modificar el correo
       */

      public function cambiarCorreoUsuario()
      {

            if ($this->isPostRequest())
            {
                  //Validar datos introducidos por el usuario
                  $validator = $this->getCambioCorreoValidator();
                  if ($validator->passes())
                  {
                        //verificar contraseña del usuario
                        if (strlen(Input::get('password')) > 0 && !Hash::check(Auth::user()->password, Input::get('password')))
                        {
                              $usuario = Auth::user();
                              //editar correo del usuario
                              if ($this->Usuario->editarUsuario($usuario->id, null, Input::get('new_email'), null, null, null, null))
                              {
                                    Session::flash('message', 'Tu correo se ha actualizado');
                                    return Redirect::to('usuario/inicio');
                              }
                              else
                              {
                                    Session::flash('error', 'Error al cambiar la contraseña');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'El password anterior no coincide con los datos de la base');
                        }
                  }
                  return Redirect::back()->withErrors($validator->messages())->withInput();
            }
            else
            {
                  return View::make('usuarios.cambiar_correo');
            }
      }

      /*
       * Funcion para recuperar contraseña perdida
       */

      public function recuperarPassword()
      {

            if ($this->isPostRequest())
            {
                  //crea un token de pasword
                  $response = $this->getPasswordRemindResponse();

                  if ($this->isInvalidUser($response))
                  {
                        Session::flash('error', Lang::get($response));
                        return Redirect::back()->withInput();
                  }

                  return Redirect::route('inicio')->with("message", Lang::get($response));
            }

            return View::make('usuarios.recuperar');
      }

      /*
       * Funcion para resetear el password 
       */

      public function regenerarPassword($token)
      {
            if ($this->isPostRequest())
            {
                  //valida los datos del usuario
                  $validator = $this->getResetValidator();
                  if ($validator->passes())
                  {
                        //obtiene las credenciales del usuario
                        $credentials = Input::only('email'
                                    , 'password'
                                    , 'password_confirmation') + compact("token");
                        //regenera el password
                        $response = $this->resetPassword($credentials);

                        if ($response === Password::PASSWORD_RESET)
                        {
                              Session::flash('message', 'Cambio de contraseña correcto');
                              return Redirect::route('inicio');
                        }

                        return Redirect::back()->withInput()->with('errors', Lang::get($response));
                  }
            }

            return View::make('usuarios.reset', compact($token));
      }

      /*
       * Funcion para terminar la sesion del usuario
       */

      public function logout()
      {
            //elimina los datos de la session
            Session::flush();
            //unloggea al usuario
            Auth::logout();
            //se despide
            Session::flash('message', 'Vuelve pronto');
            return Redirect::route("inicio");
      }

      /*
       * Elimina el usuario del sistema cuando el pago fue cancelado
       */

      public static function eliminarUsuarioPagoCancelado($usuario)
      {

            $usuariosRepository = new UsuariosRepositoryEloquent();
            //elimina al usuario 
            if ($usuariosRepository->eliminarUsuario($usuario->id))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Activar el usuario una vez que el dominio se registro
       */

      public static function activarUsuario($usuario)
      {
            $usuarioRepository = new UsuariosRepositoryEloquent();
            if ($usuarioRepository->activarUsuario($usuario))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Actualiza el pago inicial del usuario, 
       * dependiendo de lo que llegue de MercadoPago
       */

      public static function actualizarPagoInicialUsuario($usuario)
      {
            try
            {
                  $usuario->is_deudor = false;

                  if ($usuario->save())
                  {
                        return true;
                  }
                  else
                  {
                        return false;
                  }
            }
            catch (Exception $e)
            {
                  Log::error('UsuariosRepositoryEloquent . activarUsuario ' . print_r($e, true));
                  return false;
            }
      }

      /*
       * Funcion para regenerar el password
       */

      protected function resetPassword($credentials)
      {
            return Password::reset($credentials, function($user, $pass) {
                        $user->password = Hash::make($pass);
                        $user->save();
                  });
      }

      /*
       * Funcion para obtener el reminder password
       */

      protected function getPasswordRemindResponse()
      {
            return Password::remind(Input::only('email'), function($message, $user) {
                        $message->subject('Recuperación de contraseña');
                  });
      }

      /*
       * Función para saber si el usuario es invalido
       */

      protected function isInvalidUser($response)
      {
            return $response === Password::INVALID_USER;
      }

      /*
       * Funcion para obtener las validaciones del usuario
       */

      protected function getLoginValidator()
      {

            return Validator::make(Input::all(), array(
                        'correo' => 'required|email',
                        'password' => 'required|min:9',
            ));
      }

      /*
       * Funcion para validar la regeneracion de la contraseña
       */

      protected function getResetValidator()
      {
            return Validator::make(Input::all(), array(
                        'email' => 'required|email',
                        'password' => 'required',
            ));
      }

      /*
       * Validador del cambio de password
       */

      protected function getCambioPasswordValidator()
      {
            return Validator::make(Input::all(), array(
                        'old_password' => 'required',
                        'password' => 'required|min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
                        'password_confirmation' => 'required|same:password',
                        ), array(
                        'old_password.required' => 'Escriba su contraseña anterior',
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      /*
       * Validador del cambio de correo
       */

      protected function getCambioCorreoValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'required',
                        'old_email' => 'required|email',
                        'new_email' => 'required|email',
                        ), array(
                        'password.required' => 'Escriba la contraseña de su usuario',
            ));
      }

}
