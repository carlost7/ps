<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDominiosPendientes extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('dominios_pendientes', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('usuario_id')->unsigned();
                  $table->string('dominio');                  
                  $table->boolean('is_ajeno');
                  $table->integer('plan_id')->unsigned();                  
                  $table->timestamps();
                  $table->foreign('usuario_id')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');;
                  $table->foreign('plan_id')->references('id')->on('planes')->onDelete('cascade')->onUpdate('cascade');;
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::dropIfExists('dominios_pendientes');
      }

}
