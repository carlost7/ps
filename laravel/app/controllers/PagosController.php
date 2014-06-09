<?php

use PagosRepository as Pagos;
use PlanRepository as Planes;

class PagosController extends \BaseController {

      protected $Pagos;
      protected $Planes;

      public function __construct(Pagos $pagos, Planes $planes)
      {
            parent::__construct();
            $this->Pagos = $pagos;
            $this->Planes = $planes;
      }

      /*
       * Display a listing of the resource.
       *
       * @return Response
       */

      public function index()
      {
            $pagos = $this->Pagos->listar_pagos();
            return View::make('pagos.index')->with(array('pagos' => $pagos));
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $pago = $this->Pagos->obtener_pago($id);
            return View::make('pagos.show')->with(array('pago' => $pago));
      }

      /*
       * Obtener el costo del servicio a traves de ajax
       */

      public function obtenerCostoServiciosInicialesAjax()
      {
            $dominio = Input::get('dominio');
            $plan = Input::get('plan');
            $tipo_pago = Input::get('tipo_pago');
            $tiempo_servicio = Input::get('tiempo_servicio');
            $moneda = Input::get('moneda');

            return Response::json(self::obtenerCostoServiciosIniciales($dominio, $plan, $tipo_pago, $tiempo_servicio, $moneda));
      }

      /*
       * Obtener costo de los servicios
       */

      public function obtenerCostoServiciosIniciales($dominio, $plan, $tipo_pago, $tiempo_servicio, $moneda)
      {

            $planes = new PlanRepositoryEloquent();
            //Obtenemos el costo del servicio
            $costo_servicio = $planes->obtenerCostoPlan($plan, $moneda);

            //Obtenemos el costo del dominio, si es que el dominio será nuestro
            $dominio_existente = Session::get('dominio_existente');
            $costo_dominio_moneda = null;
            $descripcion_dominio = null;
            if ($dominio_existente != 1)
            {
                  $costo_dominio = DominiosController::getCostoDominio($dominio);
                  $costo_dominio_moneda = $this->convertirMoneda($costo_dominio, 'USD', $moneda);
                  $descripcion_dominio = "1 año de dominio";
            }

            if ($tipo_pago == 'anual')
            {
                  $costo_servicio = $costo_servicio->costo_anual;
                  $descripcion_servicio = 'PrimerServer * 1 año';
            }
            else
            {
                  $costo_servicio = $costo_servicio->costo_mensual * $tiempo_servicio;
                  $descripcion_servicio = 'PrimerServer * ' . $tiempo_servicio . 'meses';
            }

            $total = $costo_servicio + $costo_dominio_moneda;
            
            $costo = array('costo_servicio'=>$costo_servicio,
                  'descripcion_servicio'=>$descripcion_servicio,
                  'costo_dominio'=>$costo_dominio_moneda,
                  'descripcion_dominio'=>$descripcion_dominio,
                  'total'=>$total);
            
            return array($costo);
      }

      /*
       * Cambio de moneda
       */

      public static function convertirMoneda($amount, $from, $to)
      {
            $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
            $data = file_get_contents($url);
            preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
            $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
            return round($converted, 2);
      }

}
