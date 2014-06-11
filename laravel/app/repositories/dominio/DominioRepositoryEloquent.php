<?php

/**
 * Description of DominioRepositoryEloquent
 *
 * @author carlos
 */
class DominioRepositoryEloquent implements DominioRepository {
      /*
       * Tratar de probar si el dominio existe, o dar otras alternativas
       */

      public function comprobarDominio($dominio)
      {
            return true;
      }

      /*
       * Agregar el dominio al servidor y luego a la base de datos
       */

      public function agregarDominio($nombre_dominio, $password, $usuario_id, $plan_id)
      {
            Db::beginTransaction();
            if ($this->agregarDominioServidor($nombre_dominio, $plan_id, $password))
            {
                  Log::error('Agregar Dominio');
                  $dominio = $this->agregarDominioBase($usuario_id, $nombre_dominio, true, $plan_id);
                  if (isset($dominio->id))
                  {
                        Db::commit();
                        return $dominio;
                  }
                  else
                  {
                        Db::rollback();
                        return false;
                  }
            }
            else
            {
                  Db::rollback();
                  return false;
            }
      }

      /*
       * Funcion para agregar dominio al servidor
       */

      public function agregarDominioServidor($nombre_dominio, $plan_id, $password)
      {
            //return true;
            $plan = Plan::where('id', $plan_id)->first();
            $whmfuncion = new WHMFunciones($plan);
            $subs = explode(".", $nombre_dominio);
            $subdominio = $subs[0];
            if ($whmfuncion->agregarDominioServidor($nombre_dominio, $subdominio, $password))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Funcion para agregar dominio a la base de datos
       */

      public function agregarDominioBase($usuario_id, $nombre_dominio, $activar, $plan_id)
      {
            $dominio = new Dominio();
            $dominio->user_id = $usuario_id;
            $dominio->dominio = $nombre_dominio;
            $dominio->activo = $activar;
            $dominio->plan_id = $plan_id;
            $dominio->save();
            return $dominio;
      }

      /*
       * Funcion para eliminar el dominio del sistema
       */

      public function eliminarDominio($dominio_model)
      {

            DB::beginTransaction();
            if ($this->eliminarDominioServidor($dominio_model))
            {

                  if ($this->eliminarDominioBase($dominio_model))
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
       * Funcion para eliminar el dominio de la base de datos
       */

      public function eliminarDominioBase($dominio_model)
      {
            if ($dominio_model->delete())
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Funcion para eliminar el dominio del servidor
       */

      public function eliminarDominioServidor($dominio_model)
      {
            $whmfunciones = new WHMFunciones($dominio_model->plan);
            $domain = $dominio_model->dominio;
            $subs = explode(".", $dominio_model->dominio);
            $subdomain = $subs[0] . '_' . $dominio_model->plan->domain;

            if ($whmfunciones->eliminarDominioServidor($domain, $subdomain))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Apartar domnio para un usuario
       */

      public function apartarDominio($user_model, $dominio, $is_ajeno, $plan_model)
      {
            try
            {
                  $dominio_pendiente = new DominioPendiente();

                  $dominio_pendiente->usuario_id = $user_model->id;
                  $dominio_pendiente->dominio = $dominio;
                  $dominio_pendiente->is_ajeno = $is_ajeno;
                  $dominio_pendiente->plan_id = $plan_model->id;

                  if ($dominio_pendiente->save())
                  {
                        dd("terminado");
                        Log::info('agregado dominio pendiente');
                        return true;
                  }
                  else
                  {
                        Log::info('no se agrego dominio pendiente');
                        return false;
                  }
            }
            catch (Exception $e)
            {
                  Session::flash('error',"ocurrio un error al tratar de apartar el dominio");
                  Log::error('DominiosRepositoryEloquent. apartarDominio ' . print_r($e, true));
                  return false;
            }
      }

      public function obtenerDominioPendiente($user_model)
      {
            $dominio_pendiente = DominioPendiente::where('usuario_id', $user_model->id)->first();
            if ($dominio_pendiente->count())
            {
                  return $dominio_pendiente;
            }
            else
            {
                  return false;
            }
      }

}
