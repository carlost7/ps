<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlanes extends Migration
{

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('planes', function(Blueprint $table) {
                  $table->increments('id');
                  $table->string('nombre');
                  $table->string('name_server');
                  $table->integer('numero_correos');
                  $table->integer('quota_correos');
                  $table->integer('numero_ftps');
                  $table->integer('quota_ftps');
                  $table->integer('numero_dbs');
                  $table->integer('quota_dbs');
                  $table->timestamps();
            });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::drop('planes');
      }

}
