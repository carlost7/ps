<?php

/**
 * Description of FtpsController
 *
 * @author carlos
 */
class PagosRepositoryMercadoPago implements PagosRepository {

      public function generarLinkPago($preference_data)
      {
            $pagos = new MercadoPagoFunciones();
            $link = $pagos->create_preference($preference_data);
            return $link;
      }
      
      public function generarLinkPagoRecurrente($preapproval_data){
            $pagos = new MercadoPagoFunciones();
            $link = $pagos->create_preapproval_payment($preapproval_data);
            return $link;
      }

}
