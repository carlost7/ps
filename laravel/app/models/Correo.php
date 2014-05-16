<?php

/**
 * Modelo para manejar los correos
 *
 * @author carlos
 */
class Correo extends Eloquent
{

      protected $table = 'correos';
      protected $fillable = array('nombre', 'correo', 'redireccion');

      public function dominio()
      {
            return $this->belongsTo('Dominio');
      }

}
