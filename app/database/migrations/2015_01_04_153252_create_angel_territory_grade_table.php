<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelTerritoryGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_territory_grade', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('territory_id')->index();
            $table->integer('angel_id')->index();
            $table->enum('grade', array('root', 'admin'))->default('admin');
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
		Schema::drop('angel_territory_grade');
	}

}
