<?php

/**
 * Description of FtpsController
 *
 * @author carlos
 */
class FtpsRepositoryEloquent implements FtpsRepository
{

      protected $dominio_model;
      protected $plan;

      public function set_attributes($dominio_model)
      {
            $this->dominio_model = $dominio_model;
            $this->plan = $dominio_model->plan;
      }

      /*
       * Listar Ftps de usuarios
       * TODO: obtener size
       */

      public function listarFtps()
      {
            return Ftp::where('dominio_id', '=', $this->dominio_model->id)->get();
      }

      /*
       * Obtener Ftp unico
       * TODO: obtener size
       */

      public function obtenerFtp($id)
      {
            return $correo_model = Ftp::find($id);
      }

      /*
        1------------------------------------------
        1    Agregar Ftps
        1------------------------------------------
       */


      /*
       * Agregar Ftp a la base de datos
       */

      public function agregarFtp($username, $hostname, $home_dir, $password, $principal)
      {
            DB::beginTransaction();
            if ($this->agregarFtpServidor($username, $home_dir, $password))
            {
                  $ftp = $this->agregarFtpBase($username, $hostname, $home_dir, $principal);
                  if (isset($ftp->id))
                  {
                        DB::commit();
                        return true;
                  }
                  else
                  {
                        DB::rollback();
                        return false;
                  }
            }
            else
            {
                  DB::rollback();
                  return false;
            }
      }

      /*
       * Agregar correo a la base de datos
       */

      protected function agregarFtpBase($username, $hostname, $home_dir, $principal)
      {
            $ftp = new Ftp();
            $ftp->dominio_id = $this->dominio_model->id;
            $ftp->username = $username.'@'.$this->plan->domain;
            $ftp->hostname = $hostname;
            $ftp->homedir = $home_dir;
            $ftp->is_principal = $principal;
            $ftp->save();
            return $ftp;
      }

      /*
       * Agregar Ftp al servidor, 
       * 
       */

      protected function agregarFtpServidor($username, $home_dir, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarFtpServidor($username, $home_dir, $password))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
        |-------------------------------------
        |    Editar Ftps
        |-------------------------------------
       */

      public function editarFtp($username, $password)
      {

            if ($this->editarPasswordFtpServidor($username, $password))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Modificar las contraseñas del servidor
       */

      protected function editarPasswordFtpServidor($username, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            $usr = explode("@", $username);
            $user = $usr[0];
            if ($whmfuncion->editarPasswordFtpServidor($user, $password))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
        |-----------------------------
        |    Seccion eliminar correos
        |------------------------------
       */

      public function eliminarFtp($ftp_model, $borrar)
      {

            DB::beginTransaction();
            if ($this->eliminarFtpServidor($ftp_model, $borrar))
            {
                  if ($this->eliminarFtpBase($ftp_model))
                  {
                        DB::commit();
                        return true;
                  }
                  else
                  {
                        DB::rollback();
                        return false;
                  }
            }
            else
            {
                  DB::rollback();
                  return false;
            }
      }

      /*
       * Eliminar el correo de la base de datos
       */

      protected function eliminarFtpBase($ftp_model)
      {
            if ($ftp_model->delete())
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Eliminar el correo del servidor
       * 
       * Si el correo tiene redirección borrar la redireccion
       */

      protected function eliminarFtpServidor($ftp_model, $borrar)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarFtpServidor($ftp_model->username, $borrar))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

}
