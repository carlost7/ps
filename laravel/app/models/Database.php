<?php

/**
 * Description of Db
 *
 * @author carlos
 */
class Database extends Eloquent {

      protected $table = 'dbs';      

      public function dominio()
      {
            return $this->belongsTo('Dominio');
      }

}
