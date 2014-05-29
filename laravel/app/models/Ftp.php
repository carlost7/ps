<?php

/**
 * Modelo para manejar los ftps
 *
 * @author carlos
 */
class Ftp extends Eloquent {

      protected $table = 'ftps';      

      public function dominio()
      {
            return $this->belongsTo('Dominio');
      }

}
