
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
            if (isset($plan) && $plan->id)
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      protected function agregarPlanBase($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs)
      {
            try
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
                  if ($plan->save())
                  {
                        return $plan;
                  }
                  else
                  {
                        return null;
                  }
                  return null;
            }
            catch (Exception $e)
            {
                  Log::error('PlanRepositoryEloquent. agregarPlanBase: Error al agregar el plan ' . print_r($e, true));
                  return null;
            }
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

      public function obtenerCostoPlan($id, $moneda)
      {
            $costo_plan = CostoPlan::where('plan_id', $id, 'and')->where('moneda', $moneda)->first();
            return $costo_plan;
      }

      public function mostrarCostoPlan($id)
      {
            $costo_plan = CostoPlan::find($id);
            return $costo_plan;
      }

      public function obtenerCostosPlanes($plan_model)
      {
            $costoPlanes = CostoPlan::where('plan_id', $plan_model->id)->get();
            return $costoPlanes;
      }

      public function agregarCostoPlan($id_plan, $costo_mensual, $costo_anual, $moneda)
      {
            try
            {
                  $costo_plan = new CostoPlan;

                  $costo_plan->plan_id = $id_plan;
                  $costo_plan->costo_mensual = $costo_mensual;
                  $costo_plan->costo_anual = $costo_anual;
                  $costo_plan->moneda = $moneda;

                  if ($costo_plan->save())
                  {
                        return $costo_plan;
                  }
                  else
                  {
                        return null;
                  }
            }
            catch (Exception $e)
            {

                  Log::error('PlanRepositoryEloquent. editarCostoPlan: Error al agregar el costo del plan ' . print_r($e, true));
                  return null;
            }
      }

      public function editarCostoPlan($id, $costo_anual, $costo_mensual, $moneda)
      {
            try
            {
                  $costo_plan = CostoPlan::find($id);
                  if ($costo_plan->id)
                  {
                        $costo_plan->plan_id = $costoplan_model;
                        $costo_plan->costo_mensual = $costo_mensual;
                        $costo_plan->costo_anual = $costo_anual;
                        $costo_plan->moneda = $moneda;

                        if ($costo_plan->save())
                        {
                              return $costo_plan;
                        }
                        else
                        {
                              return null;
                        }
                  }
                  else
                  {
                        Session::put('error', 'No existe el plan a modificar');
                        return null;
                  }
            }
            catch (Exception $e)
            {

                  Log::error('PlanRepositoryEloquent. editarCostoPlan: Error al agregar el costo del plan ' . print_r($e, true));
                  return null;
            }
      }

      public function eliminarCostoPlan($id)
      {
            try
            {
                  $costoPlan = CostoPlan::find($id);
                  if ($costoPlan->id)
                  {
                        if ($costoPlan->delete())
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
                        Session::flash('error', 'El costo para el plan no existe' . print_r($e, true));
                        return false;
                  }
            }
            catch (Exception $e)
            {
                  Log::error('PlanRepositoryEloquent. eliminarCostoPlan Error al eliminar el costo del plan ' . print_r($e, true));
                  return null;
            }
      }

}
