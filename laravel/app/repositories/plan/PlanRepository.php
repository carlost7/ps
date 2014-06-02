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

      public function obtenerPlanNombre($nombre);

      public function agregarPlan($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs);

      public function editarPlan($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs);

      public function eliminarPlan($id);
}
