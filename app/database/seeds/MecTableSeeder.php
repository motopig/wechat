<?php

/**
 * 数据初始化控制器
 * 
 * @category yunke
 * @package app\database\seeds
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class MecTableSeeder extends Seeder
{
    // 数据初始化
    public function run()
    {
        $this->god();
        $this->godGrade();
    }
    
    // 平台用户初始化
    protected function god()
    {
        DB::table('god')->truncate();
        
        $god = array(
            array(
                'email' => 'jesus@no',
                'encrypt_id' => sha1('jesus@no' . md5(time())),
                'password' => Hash::make('root'),
                'disabled' => 'false',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            )
        );
        
        DB::table('god')->insert($god);
    }
    
    // 平台用户等级初始化
    protected function godGrade()
    {
        DB::table('god_grade')->truncate();
        
        $god_grade = array(
            array(
                'god_id' => 1,
                'grade' => 'root',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            )
        );
        
        DB::table('god_grade')->insert($god_grade);
    }
    
    // 平台用户信息初始化
    protected function godInfo()
    {
        DB::table('god_info')->truncate();
        
        $god_info = array(
            array(
                'god_id' => 1,
                'name' => 'jesus',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            )
        );
        
        DB::table('god_info')->insert($god_info);
    }
}
