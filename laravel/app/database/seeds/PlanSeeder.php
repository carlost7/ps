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
                        "numero_correos" => 25,
                        "quota_correos" => 250,
                        "numero_ftps" => 1,
                        "quota_ftps" => 50,
                        "numero_dbs" => 0,
                        "quota_dbs" => 0,),
                  array(
                        "nombre" => "startup",
                        "domain" => "psstartup.com",
                        "name_server" => "psstart",
                        "numero_correos" => 100,
                        "quota_correos" => 500,
                        "numero_ftps" => 2,
                        "quota_ftps" => 200,
                        "numero_dbs" => 1,
                        "quota_dbs" => 500,),
                  array(
                        "nombre" => "enterprise",
                        "domain" => "psenterprise.com",
                        "name_server" => "psent",
                        "numero_correos" => 1000,
                        "quota_correos" => 2000,
                        "numero_ftps" => 5,
                        "quota_ftps" => 1000,
                        "numero_dbs" => 5,
                        "quota_dbs" => 1000,),
            );
            foreach ($planes as $plan) {
                  Plan::create($plan);
            }
      }

}
