<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface PagosRepository {
      
      public function set_attributes($usuario_model);
      
      public function listar_pagos();
      
      public function obtener_pago($id);
      
      public function agregar_pago($concepto, $usuario_model, $monto, $moneda, $descripcion, $inicio, $vencimiento, $activo, $no_orden, $status);
      
      public function editar_pago($id,$concepto, $usuario_model, $monto, $moneda, $descripcion, $inicio, $vencimiento, $activo, $no_orden, $status);
      
      public function eliminar_pago($id);
      
      public function generar_preferencia($preference_data);
      
      public function recibir_notificacion($id);
      
      public function actualizarStatusPagos($external_reference,$status);
            
}
