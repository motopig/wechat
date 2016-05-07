<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_info', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('angel_id')->index();
            $table->text('head')->nullable();
            $table->string('name')->nullable();
            $table->string('area')->nullable();
            $table->enum('gender', array('male', 'female'))->default('male');
            $table->string('birthday')->nullable();
            $table->text('brief')->nullable();
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
		Schema::drop('angel_info');
	}

}
