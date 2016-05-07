<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class cutcut extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate:cut';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'come and cut.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

        $towerInfo = \DB::table('tower')->get();

        if($towerInfo && count($towerInfo) > 0){
//            foreach($towerInfo as $k => $v){
//                //创建云号单独数据库
//                $create_sql = "create database '{$v->encrypt_id}'";
////                if(DB::unprepared($create_sql)){
//                    $check_sql = "show tables like  '%$v->encrypt_id%' ";
//                    $pareTable = \DB::select($check_sql);
//                    $old_data = [];
//
//                    foreach($pareTable as $k => $v){
//                        $tableName = array_values(get_object_vars($v))[0];
//                        $old_data_sql = "select * from ".$tableName;
//                        $old_data[$tableName] = \DB::select($old_data_sql);
//                        if(!empty($old_data[$tableName])){
//                            foreach($old_data[$tableName] as $sk => &$sv){
//                                $old_data[$tableName][$sk] = get_object_vars($sv);
//                            }
//                        }else{
//                            $desc_table_sql = "desc table "
//                        }
//                    }
//
//                    //切换到云号库
//                    $defConf = \Config::get('database.connections.tower_conn');
//                    $defConf['database'] = $v->encrypt_id;
//                    \Config::set('database.connections.tower_conn', $defConf);
//                    \Config::set('database.default', 'tower_conn');
//
//                    foreach($old_data as $k => $v){
//
//
//
//                        $tableName = array_values(get_object_vars($v))[0];
//
//                        $create_table_sql = "create table ". $v->encrypt_id . "select * from ";
//                    }
//
////                }
//            }
        }


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
