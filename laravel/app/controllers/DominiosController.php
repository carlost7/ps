<?php

/**
 * Controlador que permite crear dominios
 *
 * @author carlos
 */
use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as Ftp;

class DominiosController extends BaseController
{

      protected $Usuario;
      protected $Dominio;
      protected $Ftp;

      public function __construct(Usuario $usuario, Dominio $dominio, Ftp $ftp)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
      }

      /*
       * Pagina inicial de dominios (inicial de la aplicación
       */

      public function iniciarDominios()
      {
            return View::make('dominios.dominios');
      }

      /*
       * Carga la vista de nuevo dominio 
       */

      public function dominioNuevo()
      {
            return View::make('dominios.nuevo');
      }

      /*
       * Carga la vista de dominio existente
       */

      public function dominioExistente()
      {
            return View::make('dominios.existente');
      }

      /*
       * Comprobar si se puede agregar el dominio, se tiene que usar ajax
       */

      public function comprobarDominio()
      {
            return true;
      }

      /*
       * Funcion para confirmar si el dominio es correcto
       * 
       * get: muestra la página de confirmación de dominio
       * post: agrega el usuario al sistema, agrega el dominio al usuario y al sistema
       *      envia al usuario a la pagina principal para entrar al dashboard
       */

      public function confirmarDominio()
      {

            if ($this->isPostRequest())
            {
                  DB::beginTransaction();
                  $validator = $this->getValidatorConfirmUser();

                  if ($validator->passes())
                  {
                        $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false);
                        if ($usuario->id != null)
                        {
                              $plan = Plan::where('nombre', '=', Input::get('plan'))->first();
                              $dominio = $this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id);
                              if (isset($dominio->id))
                              {
                                    $this->Ftp->set_attributes($dominio);
                                    $user=explode('.', $dominio->dominio);
                                    $username = $user[0];
                                    $hostname='primerserver.com';
                                    $home_dir=$dominio->dominio;
                                    if ($this->Ftp->agregarFtp($username, $hostname, $home_dir, Input::get('password'),true))
                                    {
                                          Session::put('message', 'La cuenta esta lista para usarse');
                                          DB::commit();
                                          $data = array('dominio'=>$dominio->dominio,
                                                        'usuario'=>$usuario->email,
                                                        'password'=>Input::get('password'),
                                                        'ftp_user'=>$username.'@'.$dominio->plan->name_server,
                                                        'ftp_pass'=>Input::get('password'));
                                          
                                          Mail::queue('email.welcome',$data,function($message){
                                                $message->to(Input::get('correo'),Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                          });
                                          
                                          return Redirect::to('usuario/login');
                                    }else{
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
            else
            {
                  if (Input::get('dominio') != '')
                  {
                        return View::make('dominios.confirmar', array('dominio' => Input::get('dominio')));
                  }
                  else
                  {
                        Session::flash('error', 'Se necesita un dominio para poder continuar');
                        return Redirect::back();
                  }
            }
      }

      protected function getValidatorConfirmUser()
      {
            return Validator::make(Input::all(), array(
                          'nombre' => 'required|min:4',
                          'password' => 'required|min:2',
                          'password_confirmation' => 'required|same:password',
                          'dominio' => 'required',
                          'correo' => 'required|email|unique:user,email',
                          'plan' => 'required|exists:planes,nombre',
                          'aceptar' => 'required|accepted'
            ));
      }

}
