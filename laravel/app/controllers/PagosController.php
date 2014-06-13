<?php

use PagosRepository as Pagos;
use PlanRepository as Planes;
use Carbon\Carbon;

class PagosController extends \BaseController {

      protected $Pagos;
      protected $Planes;

      public function __construct(Pagos $pagos, Planes $planes)
      {
            parent::__construct();
            $this->Pagos = $pagos;
            $this->Planes = $planes;
      }

      public function pagoCancelado()
      {
            return View::make('pagos.cancelado');
      }

      public function pagoAceptado()
      {
            return View::make('pagos.pendiente');
      }

      public function pagoPendiente()
      {
            return View::make('pagos.pendiente');
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
            $plan_id = Input::get('plan');
            $tipo_pago = Input::get('tipo_pago');
            $tiempo_servicio = Input::get('tiempo_servicio');
            $moneda = Input::get('moneda');

            return Response::json(self::obtenerCostoServiciosIniciales($dominio, $plan_id, $tipo_pago, $tiempo_servicio, $moneda));
      }

      /*
       * Obtener costo de los servicios
       */

      public static function obtenerCostoServiciosIniciales($dominio, $plan_id, $tipo_pago, $tiempo_servicio, $moneda)
      {

            $planes = new PlanRepositoryEloquent();
            //Obtenemos el costo del servicio
            $costo_servicio = $planes->obtenerCostoPlanByMoneda($plan_id, $moneda);
            //Obtenemos el costo del dominio, si es que el dominio será nuestro
            $dominio_ajeno = Session::get('dominio_ajeno');

            $costo_dominio_moneda = null;
            $descripcion_dominio = null;
            if (!$dominio_ajeno)
            {
                  $costo_dominio = DominiosController::getCostoDominio($dominio);
                  $costo_dominio_moneda = self::convertirMoneda($costo_dominio, 'USD', $moneda);
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

            $costo = array('costo_servicio' => $costo_servicio,
                  'descripcion_servicio' => $descripcion_servicio,
                  'costo_dominio' => $costo_dominio_moneda,
                  'descripcion_dominio' => $descripcion_dominio,
                  'total' => $total);

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

      /*
       * Guarda el pago de servicio en la base de datos para un usuario 
       * y genera una referencia para que el usuario pueda pagar
       * 
       * Generar los datos de preferencia
       * 
       * Guardar los datos en la base de datos.
       * 
       * 
       * 
       */

      public static function generarPagoServiciosIniciales($usuario, $dominio, $plan, $tipo_pago, $tiempo_servicio, $moneda)
      {
            $costo_total = self::obtenerCostoServiciosIniciales($dominio, $plan, $tipo_pago, $tiempo_servicio, $moneda);
            $preference_data = self::generarPreferenceDataInicial($costo_total, $usuario, $moneda);

            Log::info('PagosController. generarPagoServiciosIniciales. ' . print_r($preference_data, true));

            $pagoRepository = new PagosRepositoryMercadoPago();

            $preference = $pagoRepository->generar_preferencia($preference_data);


            $inicio = Carbon::now();
            if ($tipo_pago == 'anual')
            {
                  $vencimiento = Carbon::now()->addYear();
            }
            else
            {
                  $vencimiento = Carbon::now()->addMonths($tiempo_servicio);
            }
            $pagoRepository->set_attributes($usuario);
            $pago = $pagoRepository->agregar_pago('Pago Inicial de PrimerServer', $usuario, $costo_total[0]['costo_servicio'], $moneda, $costo_total[0]['descripcion_servicio'], $inicio, $vencimiento, true, $preference['response']['external_reference'], 'inicio');
            if (isset($pago) && $pago->id)
            {
                  if (isset($costo_total['costo_dominio']))
                  {
                        $this->Pagos->agregar_pago('Pago Dominio', $usuario, $costo_total[0]['costo_dominio'], $moneda, $costo_total[0]['descripcion_dominio'], $inicio, $vencimiento, true, $preference['response']['external_reference'], 'inicio');
                  }
                  return $preference;
            }
            else
            {
                  return null;
            }
      }

      /*
       * Generar xml con datos de la preferencia
       */

      protected static function generarPreferenceDataInicial($costo_total, $usuario, $moneda)
      {
            //Items
            $dominio = null;

            $servicio = array(
                  "title" => "PrimerServer",
                  "quantity" => 1,
                  "currency_id" => $moneda,
                  "unit_price" => floatval($costo_total[0]['costo_servicio']),
                  "description" => $costo_total[0]['descripcion_servicio'],
            );

            if (isset($costo_total[0]['costo_dominio']))
            {
                  $dominio = array(
                        "title" => "Dominio",
                        "quantity" => 1,
                        "currency_id" => $moneda,
                        "unit_price" => floatval($costo_total[0]['costo_dominio']),
                        "description" => $costo_total[0]['descripcion_dominio'],
                  );
            }

            $items = array($servicio);
            if (isset($dominio))
            {
                  array_push($items, $dominio);
            }

            //Payer

            $payer = array(
                  "name" => $usuario->username,
                  "email" => $usuario->email,
            );

            //Back Urls

            $back_urls = array(
                  "success" => URL::Route("pagos/pago_aceptado"),
                  "failure" => URL::Route("pagos/pago_cancelado"),
                  "pending" => URL::Route("pagos/pago_pendiente"),
            );

            //Preference data terminado
            return array(
                  "items" => $items,
                  "payer" => $payer,
                  "back_urls" => $back_urls,
                  "external_reference" => 'Ini_' . $usuario->id,
            );
      }

      public function obtenerIPNMercadoPago()
      {

            /*

              $id = Input::get('id');
              if (isset($id))
              {
              $response = $this->Pagos->recibir_notificacion($id);
              if (isset($response))
              {
              $external_reference = $response['external_reference'];
              }
              else
              {
              Log::error('PagosController.obtenerIPNMercadoPago No se recibio informacion de pago ID:'.$id);
              echo "no recibido";
              }
              }else{
              echo "no recibi nada";
              } */


            $result = json_decode('{"status":200,
      "response":{
            "collection":{
                  "sandbox":true,
                  "id":1402605615,
                  "site_id":"MLM",
                  "date_created":"2014-06-12T17:40:14-03:00",
                  "date_approved":"2014-06-12T17:40:14-03:00",
                  "money_release_date":"2014-06-12T17:40:14-03:00",
                  "last_modified":"2014-06-12T17:40:14-03:00",
                  "payer":{
                          "id":159395481,
                          "first_name":"primerserver.com",
                          "last_name":"World Wide Tech Ventures",
                          "phone":{
                                 "area_code":"   ",
                                 "number":"       ",
                                       "extension":""},
                          "identification":{
                                  "type":null,
                                  "number":null},
                          "email":"mercadopago@primerserver.com",
                          "nickname":"PRIMERSERVERCOMWORLDWIDETEC"},
                  "order_id":"Ini_3",
                  "external_reference":"Ini_3",
                  "reason":"PrimerServer",
                  "transaction_amount":655.74,
                  "currency_id":"MXN",
                  "net_received_amount":655.74,
                  "total_paid_amount":655.74,
                  "shipping_cost":0,
                  "status":"approved",
                  "status_detail":"accredited",
                  "installments":9,
                  "payment_type":"credit_card",
                  "marketplace":"NONE",
                  "operation_type":"regular_payment",
                  "payment_method_id":"visa",
                  "marketplace_fee":0,
                  "collector":{
                          "id":159395481,
                          "first_name":"primerserver.com",
                          "last_name":"World Wide Tech Ventures",
                          "phone":{
                                "area_code":"   ",
                                "number":"       ",
                                "extension":""},
                          "email":"mercadopago@primerserver.com",
                          "nickname":"PRIMERSERVERCOMWORLDWIDETEC"}

                          }
                          
                         
            }
}', true);
            $response = $result['response'];
            $external_reference = $response['collection']['external_reference'];
            $status = $response['collection']['status'];

            $user = $this->Pagos->actualizarStatusPagos($external_reference, $status);
            if (isset($user))
            {

                  switch ($status)
                  {
                        case 'approved':
                              $this->agregarDominio($user);
                              break;
                        case "pending":
                              echo "Pago pendiente";
                              break;
                        case "in_process":
                              echo "pago en proceso";
                              break;
                        case "rejected":
                              echo "Pago rechazado";
                              break;
                        case "refunded":
                              echo "Pago regresado";
                              break;
                        case "cancelled":
                              $this->cancelarUsuario($user);
                              break;
                        case "in_mediation":
                              echo "status en_mediacion";
                              break;
                        default :
                              echo "status incorrecto";
                              break;
                  }
            }
            else
            {
                  Log::info('PagosController . obtenerIPNMercadoPago No se encuentra ningun usuario con el numero de orden ' . $external_reference);
                  echo "no se encontro ningun usuario";
            }
      }

      public function cancelarUsuario($usuario)
      {
            $dominioPendiente = $usuario->dominioPendiente();
            
            if(DominiosController::eliminarDominioPendiente($dominio_pendiente)){
                  if(UsuariosController::eliminarUsuarioPagoCancelado($usuario)){
                        echo "usuario cancelado";
                  }else{
                        Log::error('PagosController . cancelarUsuario no se pudo eliminar usuario');
                        echo "no se pudo eliminar el usuario";
                  }
            }else{
                  Log::error('PagosController . cancelarUsuario no se pudo eliminar dominio pendiente');
                  echo "no se pudo eliminar el dominio pendiente";
            }
            
            
      }

      public function agregarDominio($usuario)
      {
            
      }

}
