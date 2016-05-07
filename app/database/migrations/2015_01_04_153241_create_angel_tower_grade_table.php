<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelTowerGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_tower_grade', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('tower_id')->index();
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
		Schema::drop('angel_tower_grade');
	}

}
