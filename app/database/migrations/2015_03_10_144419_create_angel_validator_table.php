<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelValidatorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_validator', function(Blueprint $table) {
            $table->increments('id');
            $table->string('encrypt_id')->index()->unique();
            $table->string('email')->index();
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
		Schema::drop('angel_validator');
	}

}
