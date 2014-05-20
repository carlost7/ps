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
            return User::where('id', $id)->first();
      }
      
      /*
       * Funcion para agregar usuarios
       */

      public function agregarUsuario($nombre, $password, $correo, $is_admin)
      {
            $usuario = new User();
            $usuario->username = $nombre;
            $usuario->password = Hash::make($password);
            $usuario->email = $correo;
            $usuario->is_admin = $is_admin;
            if ($usuario->save())
            {
                  return $usuario;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Funcion para eliminar usuario de la base de datos
       */

      public function eliminarUsuario($id)
      {
            $user = User::find($id);
            if ($user != null)
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

      
      public function editarUsuario($id, $nombre, $password, $correo, $is_admin)
      {
            $usuario = User::find($id);
            if ($usuario)
            {
                  $usuario->username = $nombre;
                  $usuario->password = Hash::make($password);
                  $usuario->email = $correo;
                  $usuario->is_admin = $is_admin;
                  if ($usuario->save())
                  {
                        return $usuario;
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

}
