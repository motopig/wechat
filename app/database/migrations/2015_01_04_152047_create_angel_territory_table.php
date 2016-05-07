<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelTerritoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_territory', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('territory_id')->index();
            $table->integer('angel_id')->index();
            $table->enum('disabled', array('true', 'false'))->default('false');
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
		Schema::drop('angel_territory');
	}

}
