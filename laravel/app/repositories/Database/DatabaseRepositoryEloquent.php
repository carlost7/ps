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
            return $database_model = Db::find($id);
      }

      /*
        1------------------------------------------
        1    Agregar Databases
        1------------------------------------------
       */


      /*
       * Agregar Database a la base de datos
       */

      public function agregarDatabase($username, $password, $dbname)
      {
            DB::beginTransaction();
            if ($this->agregarDatabaseServidor($username, $password, $dbname))
            {
                  $Db_model = $this->agregarDatabaseBase($username, $dbname, $this->dominio_model->id);
                  if ($Db_model->id)
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

      protected function agregarDatabaseBase($username, $dbname, $dominio)
      {
            $db = new Db();
            $db->dominio_id=$dominio;
            $db->nombre = $dbname;
            $db->usuario = $username;
            $db->save();
            return $db;
      }

      /*
       * Agregar Database al servidor, 
       * 
       */

      protected function agregarDatabaseServidor($username, $password, $dbname)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarDatabaseServidor($username, $password, $dbname))
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

      public function eliminarDatabase($Db_model)
      {

            DB::beginTransaction();
            if ($this->eliminarDatabaseServidor($Db_model))
            {
                  if ($this->eliminarDatabaseBase($Db_model))
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

      protected function eliminarDatabaseBase($Db_model)
      {
            if ($Db_model->delete())
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

      protected function eliminarDatabaseServidor($Db_model)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarDbServidor($Db_model->usuario, $Db_model->nombre))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

}
