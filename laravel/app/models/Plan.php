<?php

/**
 * Modelo para manejar los planes
 *
 * @author carlos
 */
class Plan extends Eloquent {

      protected $table = 'planes';
      protected $fillable = array(
            'nombre',
            'name_server',
            'numero_correos',
            'quota_correos',
            'numero_ftps',
            'quota_ftps',
            'numero_dbs',
            'quota_dbs');

      public function dominios()
      {
            return $this->hasMany('Dominio');
      }
      
      public function dominios_pendientes(){
            return $this->hasMany('DominioPendiente');
      }

}
