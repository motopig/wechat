<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tower', function(Blueprint $table) {
            $table->increments('id');
            $table->string('encrypt_id')->index()->unique();
            $table->integer('territory_id')->index();
            $table->string('name')->unique();
            $table->string('byname')->unique(); // 别名
            $table->string('business')->nullable(); // 主营类目
            $table->string('business_other')->nullable(); // 当business为other时自定义填写类目
            $table->text('brief')->nullable();
            $table->string('site_path')->nullable();
            $table->string('admin_path')->nullable();
            $table->text('connections')->nullable();
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
		Schema::drop('tower');
	}

}
