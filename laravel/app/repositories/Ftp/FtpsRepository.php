<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface FtpsRepository
{
      public function set_attributes($dominio_model);
      public function listarFtps();
      public function obtenerFtp($id);
      public function agregarFtp($username, $hostname, $home_dir, $password, $principal);
      public function editarFtp($username,$password);
      public function eliminarFtp($ftp_model,$borrar);
}
