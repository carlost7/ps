<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCostosPlanes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('costos_planes', function(Blueprint $table) {
                  $table->increments('id');
                  $table->integer('plan_id')->unsigned();
                  $table->decimal('costo_mensual');
                  $table->decimal('costo_anual');
                  $table->string('moneda');                  
                  $table->timestamps();
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
		Schema::drop('costos_planes');
	}

}
