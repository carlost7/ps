<?php

/**
 * Description of DominioRepositoryEloquent
 *
 * @author carlos
 */
class PlanRepositoryEloquent implements PlanRepository {

      public function listarPlanes()
      {
            $planes = Plan::all();
            return $planes;
      }

      public function mostrarPlan($id)
      {
            $plan = Plan::find($id);
            return $plan;
      }

      public function obtenerPlanNombre($nombre)
      {
            $plan = Plan::where('nombre', '=', $nombre)->first();
            if ($plan->id)
            {
                  return $plan;
            }
            else
            {
                  return null;
            }
      }

      public function agregarPlan($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs)
      {
            $plan = $this->agregarPlanBase($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs);
            if ($plan->id)
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      protected function agregarPlanServidor()
      {
            
      }

      protected function agregarPlanBase($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs)
      {
            $plan = new Plan();
            $plan->nombre = $nombre;
            $plan->domain = $dominio;
            $plan->name_server = $name_server;
            $plan->numero_correos = $numero_correos;
            $plan->quota_correos = $quota_correos;
            $plan->numero_ftps = $numero_ftps;
            $plan->quota_ftps = $quota_ftps;
            $plan->numero_dbs = $numero_dbs;
            $plan->quota_dbs = $quota_dbs;
            $plan->save();
            return $plan;
      }

      public function editarPlan($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs)
      {
            if ($this->editarPlanBase($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs, $costo_anual, $costo_mensual, $moneda))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      protected function editarPlanBase($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs, $costo_anual, $costo_mensual, $moneda)
      {
            $plan = Plan::find($id);
            if ($plan)
            {
                  $plan->nombre = $nombre;
                  $plan->domain = $dominio;
                  $plan->name_server = $name_server;
                  $plan->numero_correos = $numero_correos;
                  $plan->quota_correos = $quota_correos;
                  $plan->numero_ftps = $numero_ftps;
                  $plan->quota_ftps = $quota_ftps;
                  $plan->numero_dbs = $numero_dbs;
                  $plan->quota_dbs = $quota_dbs;
                  $plan->costo_anual = $costo_anual;
                  $plan->costo_mensual = $costo_mensual;
                  $plan->moneda = $moneda;
                  if ($plan->save())
                  {
                        return true;
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

      public function eliminarPlan($id)
      {
            if ($this->eliminarPlanBase($id))
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      protected function eliminarPlanBase($id)
      {
            $plan = Plan::find($id);

            if ($plan->delete())
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

}
