<?php

/**
 * Controlador que permite crear dominios
 *
 * @author carlos
 */
use UsuariosRepository as Usuario;
use DominioRepository as Dominio;

class DominiosController extends BaseController {

      protected $Usuario;
      protected $Dominio;

      public function __construct(Usuario $usuario, Dominio $dominio)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
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
      
      public function comprobarDominio(){
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
                        $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'),false);
                        if ($usuario->id != null)
                        {
                              $plan = Plan::where('nombre', '=', Input::get('plan'))->first();                              
                              if ($this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id))
                              {
                                    Session::put('message', 'La cuenta esta lista para usarse');
                                    DB::commit();
                                    return Redirect::to('usuario/login');
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
                  if(Input::get('dominio')!=''){
                        return View::make('dominios.confirmar', array('dominio' => Input::get('dominio')));
                  }else{
                        Session::flash('error','Se necesita un dominio para poder continuar');
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
                        'aceptar'=>'required|accepted'
            ));
      }

}
