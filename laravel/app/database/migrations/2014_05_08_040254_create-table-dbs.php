<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDbs extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('dbs', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('dominio_id')->unsigned();
                  $table->string('nombre');
                  $table->string('usuario');
                  $table->timestamps();
                  $table->foreign('dominio_id')->references('id')->on('dominios')->unsigned();
            });
      }

      /**
       * Reverse the migrations.
       * @return void
       */
      public function down()
      {
            Schema::drop('dbs');
      }

}
