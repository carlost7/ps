<?php

/**
 * Modelo para manejar los planes
 *
 * @author carlos
 */
class Pago extends Eloquent {
      protected $table = 'pagos';

      public function user()
      {
            return $this->belongsTo('User','usuario_id','id');
      }
}

