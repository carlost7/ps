<?php

/**
 * Archivo para crear el primer usuario
 *
 * @author carlos
 */
class UserSeeder extends DatabaseSeeder {

      public function run()
      {
            $users = array(
                  array(
                        "username" => "Carlos juarez",
                        "password" => Hash::make("klendactu"),
                        "email" => "carlos.juarez@t7marketing.com",
                        "is_admin" => true),
            );
            foreach ($users as $user)
            {
                  User::create($user);
            }
      }

}
