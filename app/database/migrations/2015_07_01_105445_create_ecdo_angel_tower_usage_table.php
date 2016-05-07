<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelTowerUsageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_tower_usage', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('tower_id')->index();
            $table->string('usage_type');
            $table->integer('usage_top');
            $table->integer('usage_used')->default(0);
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
