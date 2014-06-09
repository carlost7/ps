<?php

/**
 * Description of CostoPlan
 *
 * @author carlos
 */
class CostoPlan extends Eloquent{
 
      protected $table = 'costos_planes';
      
      public function dominio()
      {
            return $this->belongsTo('Planes');
      }      

}
