<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoginB2cToTowerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tower', function(Blueprint $table)
		{
            $table->text('login_b2c')->nullable();//云号对应微商城后台账号信息
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tower', function(Blueprint $table)
		{
            Schema::table('tower', function(Blueprint $table) {
                $table->dropColumn('login_b2c');
            });
		});
	}

}
