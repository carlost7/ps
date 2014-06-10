<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePagosUsuario extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('pagos', function(Blueprint $table) {
                  $table->increments('id');
                  $table->string('concepto');
                  $table->integer('usuario_id')->unsigned();
                  $table->decimal('monto');
                  $table->string('moneda');
                  $table->string('descripcion');
                  $table->date('inicio');
                  $table->date('vencimiento');
                  $table->boolean('activo');
                  $table->string('no_orden');
                  $table->string('status');
                  $table->timestamps();
                  $table->foreign('usuario_id')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');;
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::drop('pagos');
      }

}
