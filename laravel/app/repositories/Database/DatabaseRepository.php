<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface DatabaseRepository
{
      public function set_attributes($Database_model);
      public function listarDatabases();
      public function obtenerDatabase($id);
      public function agregarDatabase($username, $password, $dbname);
      public function eliminarDatabase($Database_model);
}
