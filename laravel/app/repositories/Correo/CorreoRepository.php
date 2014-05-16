<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface CorreoRepository
{
      public function set_attributes($dominio_model);
      public function listarCorreos();
      public function obtenerCorreo($id);
      public function agregarCorreo($nombre, $email, $redireccion = '', $password);
      public function editarCorreo($correo_model, $password, $redireccion);
      public function eliminarCorreo($correo_model);
}
