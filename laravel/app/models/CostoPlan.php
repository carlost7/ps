<?php

/**
 * Description of CostoPlan
 *
 * @author carlos
 */
class CostoPlan {
 
      protected $table = 'costos_planes';
      
      public function dominio()
      {
            return $this->belongsTo('Planes');
      }      

}
