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
interface DominioRepository {

      public function comprobarDominio($tld,$sld);
      
      public function obtenerDominiosSimilares($tld,$sld);

      public function agregarDominio($usuario_id, $nombre_dominio, $is_activo, $plan_id, $is_ajeno,$password);

      public function eliminarDominio($dominio_model);

      public function apartarDominio($user_model, $dominio, $is_ajeno, $plan_model);

      public function obtenerDominioPendiente($user_model);
      
      public function comprarDominio($tld,$sld, $ext_attr = array());
      
}
