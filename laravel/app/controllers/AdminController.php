<?php

/**
 * Controlador para datos del administrador
 *
 * @author carlos
 */
class AdminController extends BaseController
{
      /*
       * Página principal
       */

      public function obtenerIndex()
      {
            Return View::make('admin.inicio');
      }

      /*
       * Funcion para obtener a todos los usuarios
       */

      public function listarUsuarios()
      {
            $usuarios = UsuariosController::listarUsuarios();
            return View::make('admin.usuarios', array('usuarios' => $usuarios));
      }

      /*
       * Agregar usuarios a la base de datos
       */

      public function agregarUsuario()
      {
            if ($this->isPostRequest())
            {
                  $validator = $this->getValidatorCreateUser();

                  if ($validator->passes())
                  {
                        $usuario = UsuariosController::agregarUsuarioBase(Input::get('nombre'), Input::get('password'), Input::get('correo'));
                        if ($usuario->id != null)
                        {
                              if (DominiosController::agregarDominioServidor(Input::get('dominio'), Input::get('plan')))
                              {
                                    $dominio = DominiosController::agregarDominioBase($usuario->id, Input::get('dominio'), true, Input::get('plan'));
                                    if ($dominio->id != null)
                                    {
                                          Session::flash('message', 'Usuario agregado');
                                          return Redirect::to('admin/usuarios');
                                    }
                                    Session::flash('error', 'Error al guardar el dominio en la base de datos');
                              }
                              else
                              {
                                    Session::flash('error', 'Error al agregar dominio al servidor');
                              }
                              $usuario->delete();
                        }
                        Session::flash('error', 'Error al agregar usuario');
                  }
                  return Redirect::back()->withInput()->withErrors($validator->messages());
            }
            else
            {
                  return View::make('admin.usuarios');
            }
      }

      protected function getValidatorCreateUser()
      {
            return Validator::make(Input::all(), array(
                          'nombre' => 'required|min:4',
                          'password' => 'required|min:2',
                          'password_confirmation' => 'required|same:password',
                          'dominio' => 'required',
                          'correo' => 'required|email|unique:user,email',
                          'plan' => 'required|exists:planes,nombre',
            ));
      }

}
