<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MercadoPago
 *
 * @author carlos
 */
class MercadoPagoFunciones {

      protected $mp;

      public function __construct()
      {
            $this->mp = new MP(Config::get('payment.client_id'), Config::get('payment.client_socket'));
            $this->mp->sandbox_mode(Config::get('payment.sandbox_mode'));
      }

      public function create_preference($preference_data)
      {
            $preference = $this->mp->create_preference($preference_data);
            return $preference;
      }

      public function create_preapproval_payment($preapproval_data)
      {
            $preapproval = $this->mp->create_preapproval_payment($preapproval_data);
            return $preapproval['response'][config::get('payment.init_point')];
      }

      public function recibir_notificacion($id)
      {
            $payment_info = $this->mp->get_payment_info($id);

            // Show payment information
            if ($payment_info["status"] == 200)
            {
                  Log::error('MercadoPago: datos de notificacion '.$id.' '.$payment_info["response"]);
            }
      }

      public function prueba()
      {
            
      }

}
