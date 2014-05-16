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

      protected function agregarFtpBase($username, $hostname, $home_dir,$principal)
      {
            $ftp = new Ftp();
            $ftp->dominio_id = $this->dominio_model->id;
            $ftp->username = $username;
            $ftp->hostname = $hostname;
            $ftp->homedir = $home_dir;
            $ftp->is_principal = $principal;
            $ftp->save();
            return $correo;
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
       * Agregar Forwarder al correo
       */

      protected function agregarFwdServidor($email, $redireccion)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarForwardServidor($this->dominio_model->dominio, $email, $redireccion))
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

      public function editarFtp($username,$password)
      {

            DB::beginTransaction();
            if ($password!='')
            {
                  if (!$this->editarPasswordFtpServidor($correo_model->correo, $password))
                  {
                        DB::rollback();
                        return false;
                  }
            }

            if (isset($redireccion))
            {
                  if ($correo_model->redireccion != $redireccion)
                  {
                        if (isset($correo_model->redireccion))
                        {
                              if (!$this->eliminarForwarderServidor($correo_model->correo, $correo_model->redireccion))
                              {
                                    DB::rollback();
                                    return false;
                              }
                        }
                        if (!$this->agregarFwdServidor($correo_model->correo, $redireccion))
                        {
                              DB::rollback();
                              return false;
                        }
                  }
            }
            else
            {
                  if (isset($correo_model->redireccion))
                  {
                        if (!$this->eliminarForwarderServidor($correo_model->correo, $correo_model->redireccion))
                        {
                              DB::rollback();
                              return false;
                        }
                  }
            }


            if ($this->editarFtpBase($correo_model, $redireccion))
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

      /*
       * Editar Ftp de la base de datos 
       */

      protected function editarFtpBase($correo_model, $redireccion)
      {
            if ($redireccion)
            {
                  $correo_model->redireccion = $redireccion;
            }
            else
            {
                  $correo_model->redireccion = '';
            }

            $correo_model->save();
            return $correo_model;
      }

      /*
       * Modificar las contraseñas del servidor
       */

      protected function editarPasswordFtpServidor($correo, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->editarPasswordFtpServidor($this->dominio_model->dominio, $correo, $password))
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

      public function eliminarFtp($user,$borrar)
      {

            DB::beginTransaction();
            if ($this->eliminarFtpServidor($correo_model))
            {
                  if (isset($correo_model->redireccion))
                  {
                        $this->eliminarForwarderServidor($correo_model->correo, $correo_model->redireccion);
                  }

                  if ($this->eliminarFtpBase($correo_model))
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

      protected function eliminarFtpBase($correo_model)
      {
            if ($correo_model->delete())
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

      protected function eliminarFtpServidor($correo_model)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarFtpServidor($this->dominio_model->dominio, $correo_model->correo))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Eliminar la redireccion del servidor
       */

      protected function eliminarForwarderServidor($correo, $redireccion)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            $whmfuncion->eliminarFwdServidor($correo, $redireccion);
            return true;
      }

}
