<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoryInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('territory_info', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('territory_id')->index();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('area')->nullable();
            $table->string('id_card')->nullable();
            $table->string('company')->nullable();
            $table->string('business_licence')->nullable();
            $table->text('brief')->nullable();
            $table->enum('validator', array('true', 'false'))->default('false');
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
		Schema::drop('territory_info');
	}

}
