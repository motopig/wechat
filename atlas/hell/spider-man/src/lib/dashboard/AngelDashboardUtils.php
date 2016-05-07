<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\Angel as AngelModel;
use Ecdo\Universe\TowerDB;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Models\Tower;

/**
 * 商家用户数据获取类
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\lib\dashboard
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelDashboardUtils
{
    // 根据邮箱和密码验证用户登入有效性
    public function getOneByEmailPass($email, $password)
    {
        if (Auth::angel()->attempt(array(
            'email' => $email,
            'password' => $password
        ))) {
            return AngelModel::where('email', $email)->where('disabled', 'false')->pluck('id');
        }
    }

    // 根据用户ID获取公司信息
    public function getOneByTerritoryId($id)
    {   TowerDB::useConnUniverse();
        $dt = DB::table('angel_territory')->join('territory', 'angel_territory.territory_id', '=', 'territory.id')
            ->where('angel_territory.angel_id', $id)
            ->select('territory.id', 'territory.encrypt_id', 'territory.property')
            ->get();
//        TowerDB::useConnTower();
        return $dt[0];
    }

    // 根据用户ID获取商家店铺信息
    public function getManyByUserId($id)
    {   TowerDB::useConnUniverse();
        $dt = DB::table('angel_tower')->join('tower', 'angel_tower.tower_id', '=', 'tower.id')
            ->where('angel_tower.angel_id', $id)
            ->select('tower.id', 'tower.encrypt_id', 'tower.name', 'tower.admin_path', 'tower.disabled')->get();
//        TowerDB::useConnTower();
        return $dt;
    }
    

    // 根据用户ID判断商家公司权限是否为root
    public function getCountByTerritoryId($id)
    {   TowerDB::useConnUniverse();
        $dt = DB::table('angel_territory_grade')->where('grade', 'root')->where('angel_id', $id)->count();
//        TowerDB::useConnTower();
        return $dt;
    }

    // 获取商家公司权限等级
    public function getTerritoryGrade($id)
    {   TowerDB::useConnUniverse();
        $dt = DB::table('angel_territory_grade')->where('angel_id', $id)->pluck('grade');
//        TowerDB::useConnTower();
        return $dt;
    }

    // 获取公司验证信息
    public function getOneByTerritoryInfo($id)
    {   TowerDB::useConnUniverse();
        $dt = DB::table('territory_info')->where('territory_id', $id)->first();
//        TowerDB::useConnTower();
        return $dt;
    }

    // 获取店铺唯一识别获取店铺信息
    public function getTowerByGuid($encrypt_id)
    {
        TowerDB::useConnUniverse();
        $dt = DB::table('tower')->where('encrypt_id', $encrypt_id)->first();

        return $dt;
    }

    // 创建店铺
    public function createTower($data, $user)
    {
        $territory = $this->getOneByTerritoryId($user->id);
        $grade = $this->getTerritoryGrade($user->id);
        $tu = new TowerUtils();

        $res = true;
        DB::beginTransaction();

        $arr = ['encrypt_id' => $tu->genTowerGuid($data['name']), 'territory_id' => $territory->id,
            'name' => $data['name'], 'byname' => $data['name']];

        $tower = new \App\Models\Tower();
        $config = \Config::get('database.connections.tower_conn');
        $config['database'] = $arr['encrypt_id'];
        if ($res = self::createDatabaseStore($config, $arr)) {
            $tower->connections = serialize($config);

            $tower->encrypt_id = $arr['encrypt_id'];
            $tower->territory_id = $territory->id;
            $tower->name = $data['name'];
            $tower->byname = $tower->encrypt_id;
            $tower->business = $data['business'];
            $tower->disabled = 'true';
            if ($data['business'] == 'other') {
                $tower->business_other = $data['business_other'];
            }

            if (! $tower->save()) {
                $res = false;
            } else {
                //创建完成后 设置 tower_conn cache
                \Cache::put( 'Tower_' . $arr['encrypt_id'] . '_conn', $tower->connections , 60);

                $ag = new \Ecdo\EcdoSpiderMan\Models\AngelTower();
                $ag->angel_id = $user->id;
                $ag->tower_id = $tower->id;

                $atg = new \Ecdo\EcdoSpiderMan\AngelTowerGrade();
                $atg->angel_id = $user->id;
                $atg->tower_id = $tower->id;
                $atg->grade = $grade;

                if (! $ag->save() || ! $atg->save()) {
                    $res = false;
                }
            }
        }

        if ($res) {
            DB::commit();

            return true;
        } else {
            DB::rollBack();

            return $res;
        }
    }

    // 配置云号
    public function towerConfig($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => ''];
        DB::beginTransaction();
        
        $tower = Tower::find($data['id']);

        $tower->name = trim($data['name']);
        $tower->business = trim($data['business']);
        if (trim($data['business']) == 'other') {
            $tower->business_other = trim($data['business_other']);
        } else {
            $tower->business_other = '';
        }

        if (! $tower->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '配置云号失败';
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '配置云号成功';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    public function createDatabaseStore($res, $data)
    {
        //创建云号单独数据库
        $create_sql = "create database {$res['database']}";
        return DB::unprepared($create_sql);
    }

    /**
     * 检查云号数据库是否创建
     */
    public static function checkTowerDatabase($guid){
        TowerDB::useConnUniverse();
        $dt = DB::table('tower')->where('encrypt_id', $guid)->pluck('disabled');

        return $dt;
    }

}
