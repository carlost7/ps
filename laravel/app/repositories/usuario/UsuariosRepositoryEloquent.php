<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuariosRepositoryEloquent
 *
 * @author carlos
 */
class UsuariosRepositoryEloquent implements UsuariosRepository {
      /*
       * Funcion para listar todos los usuarios
       */

      public function listarUsuarios()
      {
            return User::where('is_admin', false)->get();
      }

      /*
        |-------------------------------------
        |    Obtener un usuario
        |-------------------------------------
       */

      public function obtenerUsuario($id)
      {
            return User::find($id);
      }

      /*
       * Funcion para agregar usuarios
       */

      public function agregarUsuario($nombre, $password, $correo, $is_admin, $is_activo, $is_deudor)
      {
            try
            {
                  $usuario = new User();

                  $usuario->username = $nombre;
                  $usuario->password = Hash::make($password);
                  $usuario->email = $correo;
                  $usuario->is_admin = $is_admin;
                  $usuario->is_activo = $is_activo;
                  $usuario->is_deudor = $is_deudor;

                  if ($usuario->save())
                  {
                        return $usuario;
                  }
                  else
                  {
                        return false;
                  }
            }
            catch (Exception $e)
            {
                  Session::flash('error', 'Error al crear el usuario');
                  Log::error('UsuariosRepositoryEloquent. Agregarusuario: ' . print_r($e, true));
                  return null;
            }
      }

      /*
       * Funcion para eliminar usuario de la base de datos
       */

      public function eliminarUsuario($id)
      {
            $user = User::find($id);
            if ($user->id)
            {
                  if ($user->delete())
                  {
                        return true;
                  }
                  else
                  {
                        return false;
                  }
            }
            else
            {
                  return false;
            }
      }

      /*
       * Funcion para editar usuario
       */

      public function editarUsuario($id, $nombre, $password, $correo, $is_admin, $is_activo, $is_deudor)
      {
            $usuario = User::find($id);
            if ($usuario->id)
            {
                  
                  try
                  {

                        if (isset($nombre))
                        {
                              $usuario->username = $nombre;
                        }
                        if (isset($password))
                        {
                              $usuario->password = Hash::make($password);
                        }
                        if (isset($correo))
                        {
                              $usuario->email = $correo;
                        }
                        if (isset($is_admin))
                        {
                              $usuario->is_admin = $is_admin;
                        }
                        if (isset($is_activo))
                        {
                              $usuario->is_activo = $is_activo;
                        }
                        if (isset($is_deudor))
                        {
                              $usuario->is_deudor = $is_deudor;
                        }
                        
                        /*
                         * Guardar el usuario
                         */
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
                        Session::flash('error', 'Ocurrio un error al editar el usuario');
                        Log::error('UsuariosRepositoryEloquent: editarUsuario ' . print_r($e, true));
                        return false;
                  }
            }
            else
            {
                  Session::flash('error', 'No se encontro el usuario a modificar');
                  return false;
            }
      }

}
