<?php

/**
 * Description of UsuariosRepository
 *
 * @author carlos
 */
interface UsuariosRepository {

      public function listarUsuarios();

      public function obtenerUsuario($id);

      public function agregarUsuario($nombre, $password, $correo, $is_admin, $is_activo, $is_deudor);

      public function editarUsuario($id, $nombre, $password, $correo, $is_admin);

      public function eliminarUsuario($id);

      public function editarPasswordUsuario($id, $password);

      public function editarCorreoUsuario($id, $correo);
}
