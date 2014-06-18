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
            if ($rrpCode == "210")
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      public function obtener_dominios_similares($sld, $tld)
      {
            $args = array(
                  "command" => "NameSpinner",
                  "SLD" => $sld,
                  "TLD" => $tld,
                  "UseHyphens" => true,
                  "SensitiveContent" => true,
                  "UseNumbers" => false,
                  "Topical" => "medium",
                  "Similar" => "medium",
                  "Related" => "medium",
                  "Basic" => "medium",
                  "MaxResults" => 5,
            );

            $this->enom_api->create_url($args);
            $resultado = $this->enom_api->getResponse();
            $count = $resultado->namespin->spincount;
            $dominios = array();
            if ($count > 0)
            {
                  for ($i = 0; $i < $count; $i++)
                  {
                        if ($resultado->namespin->domains->domain[$i]['com'] != 'n')
                        {
                              array_push($dominios, $resultado->namespin->domains->domain[$i]['name'] . ".com");
                        }

                        if ($resultado->namespin->domains->domain[$i]['net'] != 'n')
                        {
                              array_push($dominios, $resultado->namespin->domains->domain[$i]['name'] . ".net");
                        }
                  }
            }
            
            if(sizeof($dominios)){
                  return $dominios;
            }else{
                  return null;
            }            
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
