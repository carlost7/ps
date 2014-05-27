<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DominPendiente
 *
 * @author carlos
 */
class DominioPendiente extends Eloquent {

      protected $table = 'dominios_pendientes';

      public function usuario()
      {
            return $this->belongsTo('user');
      }

      public function plan()
      {
            return $this->belongsTo('Plan');
      }

}
