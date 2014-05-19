<?php

/**
 * Description of PagosController
 *
 * @author carlos
 */
class PagosController extends BaseController {

      public function obtenerPaginaInicial()
      {
            echo "Página de pagos";
      }
      
      public function obtenerFaltante()
      {
            echo "Si estas en esta página significa que aún tienes un pago pendiente";
      }

}
