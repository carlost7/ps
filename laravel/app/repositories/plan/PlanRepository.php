<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DominioRepository
 *
 * @author carlos
 */
interface PlanRepository {

      public function listarPlanes();

      public function mostrarPlan($id);

      public function agregarPlan($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs);

      public function editarPlan($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs);

      public function eliminarPlan($id);

      
      /* Costo de los planes */

      public function mostrarCostoPlan($id);
      
      public function obtenerCostoPlanByMoneda($id, $moneda);
      
      public function obtenerCostosPlanes($plan_model);

      public function agregarCostoPlan($id_plan, $costo_mensual, $costo_anual, $moneda);

      public function editarCostoPlan($id, $costo_mensual, $costo_anual, $moneda);

      public function eliminarCostoPlan($id);
      
}