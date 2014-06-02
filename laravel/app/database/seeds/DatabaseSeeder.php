<?php

class DatabaseSeeder extends Seeder {

      /**
       * Run the database seeds.
       *
       * @return void
       */
      public function run()
      {
            Eloquent::unguard();

            // $this->call('UserTableSeeder');
            $this->call('PlanSeeder');
            $this->call('UserSeeder');
            $this->call('DominioSeeder');
      }

}
