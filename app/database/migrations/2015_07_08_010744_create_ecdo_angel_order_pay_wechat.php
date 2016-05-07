<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcdoAngelOrderPayWechat extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('angel_order_pay_wechat', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('angel_order_id')->index();
            $table->string('appid');
            $table->string('mch_id');
            $table->string('device_info');
            $table->string('openid');
            $table->string('is_subscribe');
            $table->string('trade_type');
            $table->string('trade_state');
            $table->string('bank_type');
            $table->mediumInteger('total_fee');
            $table->string('fee_type');
            $table->mediumInteger('cash_fee');
            $table->string('cash_fee_type');
            $table->mediumInteger('coupon_fee');
            $table->mediumInteger('coupon_count');
            $table->string('coupon_batch_id');
            $table->string('coupon_id');
            $table->mediumInteger('coupon_id_fee');
            $table->string('transaction_id');
            $table->string('trade_state_desc');
			$table->timestamp('created_at');
            $table->timestamp('time_end');
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
