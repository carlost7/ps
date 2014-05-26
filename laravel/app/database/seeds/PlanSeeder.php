<?php

/**
 * Clase para agregar los planes a la base de datos
 *
 * @author carlos
 */
class PlanSeeder extends DatabaseSeeder
{

      public function run()
      {
            $planes = array(
                  array(
                        "nombre" => "básico",
                        "domain" => "psbasic.com",
                        "name_server" => "psbasic",
                        "numero_correos" => 10,
                        "quota_correos" => 1000,
                        "numero_ftps" => 1,
                        "quota_ftps" => 2000,
                        "numero_dbs" => 0,
                        "quota_dbs" => 0,
                        "costo_anual" => 12,
                        "costo_mensual" => 3,
                        "moneda" => "MXN",
                        ),
                  array(
                        "nombre" => "startup",
                        "domain" => "psstartup.com",
                        "name_server" => "psstart",
                        "numero_correos" => 50,
                        "quota_correos" => 1000,
                        "numero_ftps" => 1,
                        "quota_ftps" => 5000,
                        "numero_dbs" => 1,
                        "quota_dbs" => 1000,
                        "costo_anual" => 24,
                        "costo_mensual" => 5,
                        "moneda" => "MXN",
                        ),
                  array(
                        "nombre" => "enterprise",
                        "domain" => "psenterprise.com",
                        "name_server" => "psent",
                        "numero_correos" => 1000,
                        "quota_correos" => 2000,
                        "numero_ftps" => 1,
                        "quota_ftps" => 10000,
                        "numero_dbs" => 2,
                        "quota_dbs" => 1000,
                        "costo_anual" => 36,
                        "costo_mensual" => 7,
                        "moneda" => "MXN",
                        ),
            );
            foreach ($planes as $plan) {
                  Plan::create($plan);
            }
      }

}
