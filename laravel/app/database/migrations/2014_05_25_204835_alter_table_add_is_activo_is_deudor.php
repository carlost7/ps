<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddIsActivoIsDeudor extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user', function(Blueprint $table) {
                  $table->boolean('is_activo')->default(false);
                  $table->boolean('is_deudor')->default(false);
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user', function(Blueprint $table)
		{
			$table->dropColumn('is_activo');
                        $table->dropColumn('is_deudor');
		});
	}

}
