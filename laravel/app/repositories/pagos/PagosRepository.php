<?php

/**
 * Interface para manejar los correos
 *
 * @author carlos
 */
interface PagosRepository {

      public function generarLinkPago($preference_data);
      
      public function generarLinkPagoRecurrente($preapproval_data);
      
}
