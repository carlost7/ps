<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDominioIsNuestro extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dominios', function(Blueprint $table)
		{
			$table->boolean('is_nuestro');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dominios', function(Blueprint $table)
		{
			$table->dropColumn('is_nuestro');
		});
	}

}
