<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelOrderInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_order_info', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('angel_order_id')->index();
            $table->enum('order_type', array('plan', 'iot','service'))->default('plan');
            $table->integer('type_id');
            $table->integer('tower_id')->index();
            $table->text('content');
            $table->integer('count_number');
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
