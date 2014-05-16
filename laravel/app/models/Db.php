<?php

/**
 * Description of Db
 *
 * @author carlos
 */
class Db extends Eloquent
{

      protected $table = 'dbs';
      protected $fillable = array('nombre', 'usuario');

      public function dominio()
      {
            return $this->belongsTo('Dominio');
      }

}
