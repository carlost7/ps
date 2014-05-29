<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCorreos extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('correos', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('dominio_id')->unsigned();
                  $table->string('nombre');
                  $table->string('correo');
                  $table->string('redireccion')->nullable();
                  $table->timestamps();
                  $table->foreign('dominio_id')->references('id')->on('dominios')->onDelete('cascade')->onUpdate('cascade');
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::dropIfExists('correos');
      }

}
