<?php

/**
 * Description of FtpsController
 *
 * @author carlos
 */
class PagosRepositoryMercadoPago implements PagosRepository {

      public function generarPreferenciaPago($preference_data)
      {
            $pagos = new MercadoPagoFunciones();
            $preference = $pagos->create_preference($preference_data);
            return $preference;
      }

      public function generarLinkPago($preference)
      {
            $link = $preference['response'][config::get('payment.init_point')];
            return $link;
      }

      public function generarLinkPagoRecurrente($preapproval_data)
      {
            $pagos = new MercadoPagoFunciones();
            $link = $pagos->create_preapproval_payment($preapproval_data);
            return $link;
      }

      public function generarPagoBase($tipo_pago, $usuario_model, $monto, $descripcion, $inicio, $vencimiento, $activo, $id_preferencia, $status)
      {
            $pago = new Pago();
            $pago->tipo_pago = $tipo_pago;
            $pago->usuario_id = $usuario_model->id;
            $pago->monto = $monto;
            $pago->descripcion = $descripcion;
            $pago->inicio = $inicio;
            $pago->vencimiento = $vencimiento;
            $pago->activo = $activo;
            $pago->id_preferencia = $id_preferencia;
            $pago->status = $status;
            if ($pago->save())
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      public function obtenerPagosUsuario($id)
      {
            return Pago::where('usuario_id', '=', $id)->get();
      }

      public function actualizarRegistroPagoExterno($preference_id, $status)
      {
            $pagos = Pago::where('id_preferencia', $preference_id)->get();
            foreach ($pagos as $pago)
            {
                  $pago->status = $status;
                  if ($pago->save())
                  {
                        $usuario = $pago->user;                        
                        
                  }                  
            }
            
            if (isset($usuario))
            {
                  return $usuario;
            }
            else
            {
                  return false;
            }
      }

}
