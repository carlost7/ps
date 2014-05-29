<?php

/**
 * Modelo para manejar los planes
 *
 * @author carlos
 */
class Plan extends Eloquent {

      protected $table = 'planes';      

      public function dominios()
      {
            return $this->hasMany('Dominio');
      }
      
      public function dominios_pendientes(){
            return $this->hasMany('DominioPendiente');
      }

}
