<?php

/**
 * Controlador para manejar a los usuarios
 *
 * @author carlos
 */

use UsuariosRepository as UserRep;

class UsuariosController extends BaseController {
      
      protected $Usuario;
      
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

                  $validator = $this->getLoginValidator();

                  if ($validator->passes())
                  {

                        $credentials = array('email' => Input::get('correo'), 'password' => Input::get('password'));

                        if (Auth::attempt($credentials))
                        {
                              if (Auth::user()->is_admin)
                              {
                                    return Redirect::to('admin/usuarios');
                              }
                              else
                              {
                                    Session::put('dominio', Auth::user()->dominio);
                                    return Redirect::route('usuario/inicio');
                              }
                        }
                        else
                        {
                              $messages = array('password' => array('Correo o Contraseña incorrecta'));
                        }
                  }
                  else
                  {
                        $messages = $validator->messages();
                  }
                  return Redirect::route('usuario/login')->withInput()->withErrors($messages);
            }

            return View::make('usuarios.entrar');
      }

      /*
       * Página prinicipal del usuario
       */

      public function iniciar()
      {
            return View::make('usuarios.inicio');
      }

      /*
       * Funcion para modificar el password
       */

      public function cambiarPasswordUsuario()
      {

            if ($this->isPostRequest())
            {
                  $validator = $this->getCambioPasswordValidator();
                  if ($validator->passes())
                  {
                        if (strlen(Input::get('old_password')) > 0 && !Hash::check(Auth::user()->password, Input::get('old_password')))
                        {
                              $usuario = Auth::user();
                              if($this->Usuario->editarPasswordUsuario($usuario->id, Input::get('password'))){
                                    Session::flash('message','Cambio de contraseña correcto');
                                    if(Auth::User()->is_admin){
                                          return Redirect::to('admin/usuarios');
                                    }else{
                                          return Redirect::to('usuario/inicio');
                                    }
                                    
                              }else{
                                    Session::flash('error','Error al cambiar la contraseña');
                              }
                        }else{
                              Session::flash('error','El password anterior no coincide con los datos de la base');
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
                  $validator = $this->getCambioCorreoValidator();
                  if ($validator->passes())
                  {
                        if (strlen(Input::get('password')) > 0 && !Hash::check(Auth::user()->password, Input::get('password')))
                        {
                              $usuario = Auth::user();
                              if($this->Usuario->editarCorreoUsuario($usuario->id,Input::get('new_email'))){
                                    Session::flash('message','Tu correo se ha actualizado');
                                    return Redirect::to('usuario/inicio');
                              }else{
                                    Session::flash('error','Error al cambiar la contraseña');
                              }
                        }else{
                              Session::flash('error','El password anterior no coincide con los datos de la base');
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
                  $response = $this->getPasswordRemindResponse();

                  if ($this->isInvalidUser($response))
                  {
                        Session::flash('error', Lang::get($response));
                        return Redirect::back()->withInput();
                  }

                  return Redirect::back()->with("message", Lang::get($response));
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
                  $validator = $this->getResetValidator();
                  if ($validator->passes())
                  {
                        $credentials = Input::only('email'
                                    , 'password'
                                    , 'password_confirmation') + compact("token");
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
            Session::flush();
            Auth::logout();
            Session::flash('message', 'Vuelve pronto');
            return Redirect::route("inicio");
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
                        'password' => 'required',
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
                        'password_confirmation' => 'required|same:password',
            ));
      }

      protected function getCambioPasswordValidator()
      {
            return Validator::make(Input::all(), array(
                        'old_password' => 'required',
                        'password' => 'required',
                        'password_confirmation' => 'required|same:password',
            ));
      }

      protected function getCambioCorreoValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'required',
                        'old_email' => 'required|email',
                        'new_email' => 'required|email',
            ));
      }

}
