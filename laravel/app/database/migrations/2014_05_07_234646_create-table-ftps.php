<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFtps extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('ftps', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('dominio_id')->unsigned();
                  $table->string('username');
                  $table->string('hostname');
                  $table->timestamps();
                  $table->foreign('dominio_id')->references('id')->on('dominios');
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::drop('ftps');
      }

}
