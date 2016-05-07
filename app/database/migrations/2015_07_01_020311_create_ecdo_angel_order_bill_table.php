<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelOrderBillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_order_bill', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('angel_order_id')->index();
            $table->integer('angel_id')->index();
            $table->mediumInteger('pay_count');
            $table->mediumInteger('pay_coupon')->default(0);
            $table->string('pay_method');
            $table->string('pay_method_id');
            $table->string('pay_status');
			$table->timestamp('created_at');
            $table->timestamp('pay_at');
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
