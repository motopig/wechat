<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('territory', function(Blueprint $table) {
            $table->increments('id');
            $table->string('encrypt_id')->index()->unique();
            $table->enum('property', array('enterprise', 'personal'))->default('enterprise'); // 账户性质：enterprise(企业)，personal(个人)
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
		Schema::drop('territory');
	}

}
