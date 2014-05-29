<?php

/**
 * Modelo para manejar los correos
 *
 * @author carlos
 */
class Correo extends Eloquent {

      protected $table = 'correos';
      
      public function dominio()
      {
            return $this->belongsTo('Dominio');
      }
      
}
