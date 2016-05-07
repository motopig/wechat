<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginalToTowerWechatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tower_wechat', function(Blueprint $table) {
            $table->string('original')->nullable(); // 公众号原始ID
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tower_wechat', function(Blueprint $table) {
            $table->dropColumn('original');
        });
	}

}
