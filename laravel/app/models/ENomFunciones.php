<?php

/**
 * Description of ENomFunciones
 *
 * @author carlos
 */
class ENomFunciones {

      protected $enom_api;

      public function __construct()
      {
            $this->enom_api = new enomapi();
      }

      public function checar_dominio($sld, $tld)
      {

            $args = array(
                  "command" => 'check',
                  "sld" => $sld,
                  "tld" => $tld,
            );

            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
            
            $rrpCode = $resultado->RRPCode;
            if($rrpCode=="210"){
                  return true;
            }else{
                  return false;
            }            
            
      }

      public function obtener_dominios_similares($sld, $tld)
      {
            $args = array(
                  "command" => "NameSpinner",
                  "SLD" => $sld,
                  "TLD" => $tld,
                  "UseHyphens" => false,
                  "UseNumbers" => false,
                  "Topical" => "high",
                  "Similar" => "medium",
                  "Related" => "high",
                  "Basic" => "low",
                  "MaxResults" => 5,
            );

            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();            
            return $resultado;
      }

      public function comprar($sld, $tld)
      {
            $args = array(
                  "command" => "Purchase",
                  "sld" => $sld,
                  "tld" => $tld,
                  "useDNS" => "default"
            );
            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
      }

      protected function getExternalAttributes()
      {
            $args = array(
                  "command" => "GetExtAttributes",
                  "tld" => ca
            );
            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
      }

      public function obtener_status_dominio($sld, $tld, $order_id)
      {
            $args = array(
                  "command" => "GetDomainStatus",
                  "sld" => $sld,
                  "tld" => $tld,
                  "orderid" => $order_id,
                  "ordertype" => purchase,
            );
            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
      }

      public function obtener_lista_dominios()
      {
            $args = array("command" => "GetDomains");
            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
      }

      public function obtener_informacion_dominio($sld, $tld)
      {
            $args = array(
                  "command" => "GetDomainInfo",
                  "sld" => $sld,
                  "tld" => $tld,
            );
            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
      }

      public function renovar()
      {
            $args = array(
                  "command" => "Extend",
                  "sld" => $sld,
                  "tld" => $tld,
                  "NumYears" => 1,
            );
      }

}
