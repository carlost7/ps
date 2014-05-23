<?php

/**
 * Description of PagosController
 *
 * @author carlos
 */
use PagosRepository as Pagos;

class PagosController extends BaseController {

      protected $Pagos;

      public function __construct(Pagos $pagos)
      {
            parent::__construct();
            $this->Pagos = $pagos;
      }

      public function obtenerPaginaInicial()
      {
            echo "Página de pagos";
      }

      public function obtenerFaltante()
      {
            echo "Si estas en esta página significa que aún tienes un pago pendiente";
      }

      /*
       * Funcion para confirmar si el dominio es correcto
       * 
       * get: muestra la página de confirmación de dominio
       * post: agrega el usuario al sistema, agrega el dominio al usuario y al sistema
       *      envia al usuario a la pagina principal para entrar al dashboard
       */

      public function confirmarRegistro()
      {
            if ($this->isPostRequest())
            {
                  $preference_data = array(
                        "items" => array(
                              array(
                                    "title" => 'Plan Básico',
                                    "quantity" => 1,
                                    "currency_id" => "MXN",
                                    "unit_price" => 10.00,
                                    //"picture_url" => "",
                                    "id" => ''
                              )
                        )
                        
                        
                  );

                  $preference = $this->Pagos->generarLinkPago($preference_data);

                  return Redirect::away($preference);
            }
            else
            {
                  if (Input::get('dominio') != '')
                  {
                        Session::put('posible_dominio', Input::get('dominio'));
                        Session::put('existente', Input::get('existente'));
                        return View::make('dominios.confirmar', array('dominio' => Input::get('dominio')));
                  }
                  else
                  {
                        Session::flash('error', 'Se necesita un dominio para poder continuar');
                        return Redirect::back();
                  }
            }
      }

}
