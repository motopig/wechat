<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel', function(Blueprint $table) {
            $table->increments('id');
            $table->string('encrypt_id')->index()->unique();
            $table->string('email')->index()->unique();
            $table->string('mobile')->index()->nullable();
            $table->string('password');
            $table->text('remember_token')->nullable();
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
		Schema::drop('angel');
	}

}
