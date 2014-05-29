<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDominios extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('dominios', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('user_id')->unsigned()->unique();
                  $table->string('dominio');
                  $table->boolean('activo');
                  $table->integer('plan_id')->unsigned();
                  $table->timestamps();
                  $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
                  $table->foreign('plan_id')->references('id')->on('planes')->onDelete('cascade')->onUpdate('cascade');
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::drop('dominios');
      }

}
