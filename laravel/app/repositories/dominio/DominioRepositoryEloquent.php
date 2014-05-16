<?php

/**
 * Description of DominioRepositoryEloquent
 *
 * @author carlos
 */
class DominioRepositoryEloquent implements DominioRepository {
      /*
       * Funcion para comprar si el dominio esta disponible
       */

      public function comprobarDominio($dominio)
      {
            return true;
      }

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

}
