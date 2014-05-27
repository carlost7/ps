<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDomainnamePlanes extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::table('planes', function(Blueprint $table) {
                  $table->string('domain')->after('nombre');
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::table('planes', function(Blueprint $table) {
                  $table->dropColumn('domain');
            });
      }

}
