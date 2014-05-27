<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHomedirFtps extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::table('ftps', function(Blueprint $table) {
                  $table->string('homedir')->after('hostname');
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::table('ftps', function(Blueprint $table) {
                  $table->dropColumn('homedir');
            });
      }

}
