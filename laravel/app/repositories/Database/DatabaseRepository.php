<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface DatabaseRepository
{
      public function set_attributes($dominio_model);
      public function listarDatabases();
      public function obtenerDatabase($id);
      public function agregarDatabase($nombre, $email, $redireccion = '', $password);
      public function editarDatabase($correo_model, $password, $redireccion);
      public function eliminarDatabase($correo_model);
}
