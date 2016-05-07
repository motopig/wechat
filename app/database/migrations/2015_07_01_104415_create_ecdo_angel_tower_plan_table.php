<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelTowerPlanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_tower_plan', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('angel_id')->index();
            $table->integer('tower_id')->index()->unique();
            $table->string('tower_grade');
            $table->enum('auto_close', array('true', 'false'))->default('false');
            $table->timestamp('end_at');
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
		//
	}

}
