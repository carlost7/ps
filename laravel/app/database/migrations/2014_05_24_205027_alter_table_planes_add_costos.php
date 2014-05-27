<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePlanesAddCostos extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::table('planes', function(Blueprint $table) {
                  $table->decimal('costo_anual');
                  $table->decimal('costo_mensual');
                  $table->string('moneda', 3);
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
                  $table->dropColumn('costo_anual');
                  $table->dropColumn('costo_mensual');
                  $table->dropColumn('moneda');
            });
      }

}
