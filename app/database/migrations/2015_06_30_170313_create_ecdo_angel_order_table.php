<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_order', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('angel_id')->index();
            $table->enum('status', array('ready', 'paid','send','finish','cancel','sendback','refund'))->default('ready');
            $table->mediumInteger('order_count');
            $table->mediumInteger('pay_count');
            $table->mediumInteger('order_coupon')->default(0);
            $table->enum('order_type',array('plan','iot','service'))->default('plan');
            $table->string('pay_method');
            $table->string('recive_province')->nullable();
            $table->string('recive_city')->nullable();
            $table->string('recive_district')->nullable();
            $table->string('recive_address')->nullable();
            $table->text('remark')->nullable();
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
		//
	}

}
