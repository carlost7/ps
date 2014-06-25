<?php

/**
 * Description of CorreosController
 *
 * @author carlos
 */
class CorreosRepositoryEloquent implements CorreosRepository {

      protected $dominio_model;
      protected $plan;

      public function set_attributes($dominio_model)
      {
            $this->dominio_model = $dominio_model;
            $this->plan = $dominio_model->plan;
      }

      /*
       * Listar Correos de usuarios
       * TODO: obtener size
       */

      public function listarCorreos()
      {
            return Correo::where('dominio_id', '=', $this->dominio_model->id)->paginate(10);
      }

      public function contarCorreos()
      {
            return Correo::where('dominio_id', $this->dominio_model->id)->count();
      }
      
      public function listarQuotas()
      {
            $domain = $this->dominio_model->dominio;
            $whmfuncion = new WHMFunciones($this->plan);
            $usedQuotas = $whmfuncion->obtenerQuotaCorreosServidor($domain);
            return $usedQuotas;
      }

      /*
       * Obtener Correo unico
       * TODO: obtener size
       */

      public function obtenerCorreo($id)
      {
            return $correo_model = Correo::find($id);
      }

      public function obtenerUsedQuota($correo_model)
      {

            $correo = explode('@', $correo_model->correo);
            $username = $correo[0];
            $domain = $correo[1];
            $whmfuncion = new WHMFunciones($this->plan);
            $usedQuota = $whmfuncion->obtenerQuotaCorreoServidor($username, $domain);
            return $usedQuota;
      }

      /*
        1------------------------------------------
        1    Agregar Correos
        1------------------------------------------
       */


      /*
       * Agregar Correo a la base de datos
       */

      public function agregarCorreo($nombre, $email, $redireccion = '', $password)
      {
            DB::beginTransaction();
            if ($this->agregarCorreoServidor($email, $password))
            {
                  if ($redireccion != '')
                  {
                        if (!$this->agregarFwdServidor($email, $redireccion))
                        {
                              DB::rollback();
                              return false;
                        }
                  }
                  $correo = $this->agregarCorreoBase($nombre, $email, $this->dominio_model->id, $redireccion);
                  if ($correo->id)
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

      protected function agregarCorreoBase($nombre, $email, $dominio, $redireccion = '')
      {
            $correo = new Correo();
            $correo->nombre = $nombre;
            $correo->correo = $email;
            $correo->dominio_id = $dominio;
            $correo->redireccion = $redireccion;
            $correo->save();
            return $correo;
      }

      /*
       * Agregar Correo al servidor, 
       * 
       */

      protected function agregarCorreoServidor($correo, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarCorreoServidor($this->dominio_model->dominio, $correo, $password))
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
        |    Editar Correos
        |-------------------------------------
       */

      public function editarCorreo($correo_model, $password, $redireccion)
      {

            DB::beginTransaction();
            if ($password != '')
            {
                  if (!$this->editarPasswordCorreoServidor($correo_model->correo, $password))
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


            if ($this->editarCorreoBase($correo_model, $redireccion))
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
       * Editar Correo de la base de datos 
       */

      protected function editarCorreoBase($correo_model, $redireccion)
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

      protected function editarPasswordCorreoServidor($correo, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->editarPasswordCorreoServidor($this->dominio_model->dominio, $correo, $password))
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

      public function eliminarCorreo($correo_model)
      {

            DB::beginTransaction();
            if ($this->eliminarCorreoServidor($correo_model))
            {
                  if (isset($correo_model->redireccion))
                  {
                        $this->eliminarForwarderServidor($correo_model->correo, $correo_model->redireccion);
                  }

                  if ($this->eliminarCorreoBase($correo_model))
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

      protected function eliminarCorreoBase($correo_model)
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

      protected function eliminarCorreoServidor($correo_model)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarCorreoServidor($this->dominio_model->dominio, $correo_model->correo))
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
