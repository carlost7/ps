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
            return $database_model = Database::find($id);
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
                  $Database_model = $this->agregarDatabaseBase($username, $dbname, $this->dominio_model->id);
                  if ($Database_model->id)
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
            $database_model = new Database();
            $database_model->dominio_id=$dominio;
            $database_model->nombre = $this->plan->name_server."_".$dbname;
            $database_model->usuario = $this->plan->name_server."_".$username;
            $database_model->save();
            return $database_model;
      }

      /*
       * Agregar Database al servidor, 
       * 
       */

      protected function agregarDatabaseServidor($username, $password, $dbname)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->agregarDbServidor($username, $password, $dbname))
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

      public function eliminarDatabase($Database_model)
      {

            DB::beginTransaction();
            if ($this->eliminarDatabaseServidor($Database_model))
            {
                  if ($this->eliminarDatabaseBase($Database_model))
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

      protected function eliminarDatabaseBase($Database_model)
      {
            
            if ($Database_model->delete())
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

      protected function eliminarDatabaseServidor($Database_model)
      {
            $whmfuncion = new WHMFunciones($this->plan);
            if ($whmfuncion->eliminarDbServidor($Database_model->usuario, $Database_model->nombre))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

}
