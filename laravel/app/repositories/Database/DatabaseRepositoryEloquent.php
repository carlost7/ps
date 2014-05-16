<?php

/**
 * Description of DatabasesController
 *
 * @author carlos
 */
class DatabaseRepositoryEloquent implements DatabaseRepository
{

      protected $dominio_model;
      protected $plan;

      public function set_attributes($dominio_model)
      {
            $this->dominio_model = $dominio_model;
            $this->plan = $dominio_model->plan;
      }

      /*
       * Listar Databases de usuarios
       * TODO: obtener size
       */

      public function listarDatabases()
      {
            return Database::where('dominio_id', '=', $this->dominio_model->id)->get();
      }

      /*
       * Obtener Database unico
       * TODO: obtener size
       */

      public function obtenerDatabase($id)
      {
            return $correo_model = Database::find($id);
      }

      /*
        1------------------------------------------
        1    Agregar Databases
        1------------------------------------------
       */


      /*
       * Agregar Database a la base de datos
       */

      public function agregarDatabase($nombre, $email, $redireccion = '', $password)
      {
            DB::beginTransaction();
            if ($this->agregarDatabaseServidor($email, $password))
            {
                  if ($redireccion != '')
                  {
                        if (!$this->agregarFwdServidor($email, $redireccion))
                        {
                              DB::rollback();
                              return false;
                        }
                  }
                  $correo = $this->agregarDatabaseBase($nombre, $email, $this->dominio_model->id, $redireccion);
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

      protected function agregarDatabaseBase($nombre, $email, $dominio, $redireccion = '')
      {
            $correo = new Database();
            $correo->nombre = $nombre;
            $correo->correo = $email;
            $correo->dominio_id = $dominio;
            $correo->redireccion = $redireccion;
            $correo->save();
            return $correo;
      }

      /*
       * Agregar Database al servidor, 
       * 
       */

      protected function agregarDatabaseServidor($correo, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarDatabaseServidor($this->dominio_model->dominio, $correo, $password))
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
        |    Editar Databases
        |-------------------------------------
       */

      public function editarDatabase($correo_model, $password, $redireccion)
      {

            DB::beginTransaction();
            if ($password!='')
            {
                  if (!$this->editarPasswordDatabaseServidor($correo_model->correo, $password))
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


            if ($this->editarDatabaseBase($correo_model, $redireccion))
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
       * Editar Database de la base de datos 
       */

      protected function editarDatabaseBase($correo_model, $redireccion)
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

      protected function editarPasswordDatabaseServidor($correo, $password)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->editarPasswordDatabaseServidor($this->dominio_model->dominio, $correo, $password))
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

      public function eliminarDatabase($correo_model)
      {

            DB::beginTransaction();
            if ($this->eliminarDatabaseServidor($correo_model))
            {
                  if (isset($correo_model->redireccion))
                  {
                        $this->eliminarForwarderServidor($correo_model->correo, $correo_model->redireccion);
                  }

                  if ($this->eliminarDatabaseBase($correo_model))
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

      protected function eliminarDatabaseBase($correo_model)
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

      protected function eliminarDatabaseServidor($correo_model)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarDatabaseServidor($this->dominio_model->dominio, $correo_model->correo))
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
