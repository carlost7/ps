<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface CorreosRepository {

      public function set_attributes($dominio_model);

      public function listarCorreos();

      public function listarQuotas();

      public function obtenerCorreo($id);

      public function agregarCorreo($nombre, $email, $redireccion = '', $password);

      public function editarCorreo($correo_model, $password, $redireccion);

      public function eliminarCorreo($correo_model);

      public function obtenerUsedQuota($correo_model);
}
