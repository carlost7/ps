<?php

/**
 * Clase para agregar los planes a la base de datos
 *
 * @author carlos
 */
class CostosPlanesSeeder extends DatabaseSeeder {

      public function run()
      {
            $costos = array(
                  array(
                        "plan_id" => 1,
                        "costo_mensual" => 50.00,
                        "costo_anual" => 300.00,
                        "moneda" => "MXN",                        
                  ),
                  array(
                        "plan_id" => 2,
                        "costo_mensual" => 100.00,
                        "costo_anual" => 500.00,
                        "moneda" => "MXN",                        
                  ),
                  array(
                        "plan_id" => 3,
                        "costo_mensual" => 150.00,
                        "costo_anual" => 800.00,
                        "moneda" => "MXN",                        
                  ),
            );
            foreach ($costos as $costo)
            {
                  CostoPlan::create($costo);
            }
      }

}
