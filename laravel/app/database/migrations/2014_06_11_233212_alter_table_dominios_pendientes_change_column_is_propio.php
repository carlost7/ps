<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDominiosPendientesChangeColumnIsPropio extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dominios_pendientes', function(Blueprint $table)
		{
			$table->boolean('is_ajeno');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dominios_pendientes', function(Blueprint $table)
		{
			$table->dropColumn('is_ajeno');
		});
	}

}
