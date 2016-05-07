<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGodGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('god_grade', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('god_id')->index();
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
		Schema::drop('god_grade');
	}

}
