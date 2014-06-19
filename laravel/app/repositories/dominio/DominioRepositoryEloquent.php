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

      public function comprobarDominio($tld, $sld)
      {
            $enomFunciones = new ENomFunciones();
            if ($enomFunciones->checar_dominio($sld, $tld))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      public function obtenerDominiosSimilares($tld, $sld)
      {
            $enomFunciones = new ENomFunciones();
            $dominios_similares = $enomFunciones->obtener_dominios_similares($sld, $tld);
            return $dominios_similares;
      }

      /*
       * Agregar el dominio al servidor y luego a la base de datos
       */

      public function agregarDominio($usuario_id, $nombre_dominio, $is_activo, $plan_id, $is_ajeno, $password)
      {

            if ($this->agregarDominioServidor($nombre_dominio, $plan_id, $password))
            {
                  $dominio = $this->agregarDominioBase($usuario_id, $nombre_dominio, $is_activo, $plan_id, $is_ajeno);
                  if (isset($dominio->id))
                  {

                        return $dominio;
                  }
                  else
                  {

                        return false;
                  }
            }
            else
            {
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

      public function agregarDominioBase($usuario_id, $nombre_dominio, $is_activo, $plan_id, $is_ajeno)
      {
            try
            {
                  $dominio = new Dominio();
                  $dominio->user_id = $usuario_id;
                  $dominio->dominio = $nombre_dominio;
                  $dominio->activo = $is_activo;
                  $dominio->plan_id = $plan_id;
                  $dominio->is_ajeno = $is_ajeno;
                  if ($dominio->save())
                  {
                        return $dominio;
                  }
                  else
                  {
                        return null;
                  }
            }
            catch (Exception $e)
            {
                  $data = array('respuesta' => print_r($e));
                  Mail::queue('email.error_agregar_dominio', $data, function($message) {
                        $message->to('carlos.juarez@t7marketing.com', "Administrador")->subject('Error al agregar el dominio');
                  });
                  Log::error('DominiosRepositoryEloquent. agregarDominioBase ' . print_r($e));
            }
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
                  Session::flash('error', "ocurrio un error al tratar de apartar el dominio");
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

      public function eliminarDominioPendiente($id)
      {
            try
            {
                  $dominio_pendiente = DominioPendiente::find($id);
                  if ($dominio_pendiente->delete())
                  {
                        return true;
                  }
                  else
                  {
                        return false;
                  }
            }
            catch (Exception $e)
            {
                  Log::error('DominioRepositoryEloquent . eliminarDominiosPendiente ' . print_r($e, true));
            }
      }

      public function comprarDominio($tld, $sld, $ext_attr = array())
      {
            $enomFunciones = new ENomFunciones();
            if ($enomFunciones->comprar($sld, $tld))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

}
