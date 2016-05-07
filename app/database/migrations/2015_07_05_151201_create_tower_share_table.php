<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerShareTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tower_share', function(Blueprint $table) {
            $table->increments('id');
            // 0:微信开放平台ticket
            $table->integer('type')->default(0); // 公用模块类型
            $table->text('content')->nullable(); // 公用模块内容
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
		Schema::drop('tower_share');
	}

}
