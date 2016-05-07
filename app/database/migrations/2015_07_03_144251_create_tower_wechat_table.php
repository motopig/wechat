<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerWechatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tower_wechat', function(Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->unique(); // 云号
            $table->string('appid')->unique(); // 授权应用ID
            $table->text('authorizer_refresh_token'); // 授权公众号刷新令牌
            $table->text('info')->nullable(); // 授权公众号信息
            $table->text('func_info')->nullable(); // 授权公众号接口权限
            $table->enum('disabled', array('true', 'false'))->default('false'); // 取消授权(false:未取消,true:取消)
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
		Schema::drop('tower_wechat');
	}

}
