<?php

/**
 * Description of FtpsController
 *
 * @author carlos
 */
class PagosRepositoryMercadoPago implements PagosRepository {

      protected $usuario_model;

      public function agregar_pago($concepto, $usuario_model, $monto, $moneda,$descripcion, $inicio, $vencimiento, $activo, $no_orden, $status)
      {
            try
            {
                  $pago = new Pago();
                  $pago->concepto = $concepto;
                  $pago->usuario_id = $usuario_model->id;
                  $pago->monto = $monto;
                  $pago->moneda = $moneda;
                  $pago->descripcion = $descripcion;
                  $pago->inicio = new DateTime($inicio);
                  $pago->vencimiento = new DateTime($vencimiento);
                  $pago->activo = $activo;
                  $pago->no_orden = $no_orden;
                  $pago->status = $status;
                  
                  if ($pago->save())
                  {
                        return $pago;
                  }
                  else
                  {
                        Session::flash('error','No se guardo el pago en la base de datos');
                        return null;
                  }
            }
            catch (Exception $e)
            {
                  Log::error('PagosRepositoryMercadoPago.Agregar_pago ' . print_r($e, true));
                  Session::flash('error', 'Ocurrio un error al guardar el pago en la base de datos');
                  return null;
            }
      }

      public function editar_pago($id, $concepto, $usuario_model, $monto, $moneda, $descripcion, $inicio, $vencimiento, $activo, $no_orden, $status)
      {
            try
            {
                  $pago = Pago::find($id);

                  if (isset($pago))
                  {
                        $pago->concepto = $concepto;
                        $pago->usuario_id = $usuario_model->id;
                        $pago->monto = $monto;
                        $pago->moneda = $moneda;
                        $pago->descripcion = $descripcion;
                        $pago->inicio = new DateTime($inicio);
                        $pago->vencimiento = new DateTime($vencimiento);
                        $pago->activo = $activo;
                        $pago->no_orden = $no_orden;
                        $pago->status = $status;

                        if ($pago->save())
                        {
                              return $pago;
                        }
                        else
                        {
                              return null;
                        }
                  }else{
                        Session::put('error','No existe el pago en la base');
                        return null;
                  }
            }
            catch (Exception $e)
            {
                  Log::error('PagosRepositoryMercadoPago.Agregar_pago ' . print_r($e, true));
                  Session::flash('error', 'Ocurrio un error al editar el pago en la base de datos');
                  return null;
            }
      }

      public function eliminar_pago($id)
      {
       
            try{
                  
                  $pago = Pago::find($id);
                  
                  if(isset($pago)&& $pago->id){
                        
                  }else{
                        
                  }
                  
            }catch(Exception $e){
                  Log::error('PagosRepositoryMercadoPago.Agregar_pago ' . print_r($e, true));
                  Session::flash('error', 'Ocurrio un error al eliminar el pago de la base de datos');
                  return null;
            }
            
      }

      public function generar_preferencia($preference_data)
      {
            $pagos = new MercadoPagoFunciones();
            $preference = $pagos->create_preference($preference_data);
            return $preference;
      }

      public function listar_pagos()
      {
            $pagos = Pago::where('usuario_id', $this->usuario_model->id)->get();
            return $pagos;
      }

      public function obtener_pago($id)
      {
            $pagos = Pago::find($id);
            return $pago;
      }

      public function set_attributes($usuario_model)
      {
            $this->usuario_model = $usuario_model;
      }

      
      function recibir_notificacion($id){
            $pagos = new MercadoPagoFunciones();
            $resultado = $pagos->recibir_notificacion($id);            
            log::info('resultado: '.$resultado);
            if(isset($resultado)){
                  Log::info('PagosRepositoryMercadoPago. recibirNotificaciones '.print_r($resultado));
                  return true;
            }else{
                  return false;
            }
      }
}