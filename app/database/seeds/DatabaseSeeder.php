<?php

/**
 * 数据初始化执行类
 *
 * @category yunke
 * @package app\database\seeds
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        
        $this->call('MecTableSeeder');
    }
}
