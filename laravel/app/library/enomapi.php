<?php

/*
 * Class to work with the eNom webapi 
 */

/**
 * Description of enomapi
 *
 * @author carlos
 */
class enomapi {

      private $uid;
      private $pw;
      private $url;
      private $response_type;
      private $xml;
      
      function __construct()
      {
            $this->uid = Config::get('enom.uid');
            $this->pw = Config::get('enom.pw');
            $this->response_type = Config::get('enom.responsetype');
            $this->url = Config::get('enom.url');
      }

      public function getUid()
      {
            return $this->uid;
      }

      public function getPw()
      {
            return $this->pw;
      }

      public function getUrl()
      {
            return $this->url;
      }

      public function getXml()
      {
            return $this->xml;
      }
      
      public function getResponse_type()
      {
            return $this->response_type;
      }

      public function setUid($uid)
      {
            $this->uid = $uid;
            return $this;
      }

      public function setPw($pw)
      {
            $this->pw = $pw;
            return $this;
      }

      public function setUrl($url)
      {
            $this->url = $url;
            return $this;
      }

      public function setXml($xml)
      {
            $this->xml = $xml;
            return $this;
      }

      public function setResponse_type($response_type)
      {
            $this->response_type = $response_type;
            return $this;
      }

      public function create_url($args = array()){
            
            if(!is_array($args)){                  
                  Log::error('enom_api, create_url: Tiene que especificar los argumentos para crear la url');
                  return false;
            }
            
            if(!$this->validate_command($args)){
                  Log::error('enom_api, create_url: No agrego un comando para ejecutar la consulta');
                  return false;
            }
            
            $user_data = array('responsetype'=>$this->response_type,'uid'=>$this->uid,'pw'=>$this->pw);
            $auth = http_build_query($user_data, '', '&');
            
            $data = http_build_query($args);
            
            $this->url .= $auth.'&'.$data; 
            
            Log::info('enomapi. Create_url: '.$this->url);
            
            return $this->url;
            
      }
      
      protected function validate_command($args = array()){
            if(array_key_exists('command', $args)){
                  return true;
            }else{
                  return false;
            }
      }
      
      public function getResponse(){
            $this->xml = simplexml_load_file($this->url);
            return $this->xml;            
      }      

}
