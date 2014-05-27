<?php

/**
 * Archivo para crear el primer usuario
 *
 * @author carlos
 */
class DominioSeeder extends DatabaseSeeder {

      public function run()
      {
            $dominios = array(
                  array(
                        "user_id" => 1,
                        "dominio" => 't7test.com',
                        "activo" => true,
                        "plan_id" => 3,)
            );
            foreach ($dominios as $dominio)
            {
                  Dominio::create($dominio);
            }
      }

}
