<?php

/**
 * Modelo para manejar los planes
 *
 * @author carlos
 */
class Plan extends Eloquent
{

      protected $table = 'planes';
      protected $fillable = array('nombre', 'correos', 'ftps', 'dbs');

      public function dominios()
      {
            return $this->hasMany('Dominio');
      }

}
