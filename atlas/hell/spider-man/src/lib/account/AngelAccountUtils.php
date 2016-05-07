<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\Angel;
use Ecdo\EcdoSpiderMan\AngelInfo;
use Ecdo\EcdoSpiderMan\AngelTerritory;
use Ecdo\EcdoSpiderMan\AngelTerritoryGrade;
use Ecdo\EcdoSpiderMan\Models\AngelTower;
use Ecdo\EcdoSpiderMan\AngelTowerGrade;
use Ecdo\EcdoSpiderMan\AngelValidator;
use App\Models\Territory;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * 商家用户数据获取类
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\lib\account
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelAccountUtils
{
    // 根据邮箱和密码验证用户登入有效性
    public function getOneByEmailPass($email, $password)
    {
        if (Auth::angel()->attempt(array(
            'email' => $email,
            'password' => $password
        ))) {
            return Angel::where('email', $email)->where('disabled', 'false')->pluck('id');
        }
    }

    // 商家注册账号临时储存
    public function createAngelValidator($email,$refer="",$updated_at="")
    {
        $av = new AngelValidator();

        $av->email = $email;
        $av->encrypt_id = sha1($email . md5(time()));
        if(!empty($refer)){
            $av->refer = $refer;
        }
        if(!empty($updated_at)){
            $av->updated_at = $updated_at;
        }

        if ($av->save()) {
            return $av->encrypt_id;
        } else {
            return false;
        }
    }

    // 根据邮箱获取商家唯一ID
    public function getEmailByEncryptId($email)
    {
        return Angel::where('email', $email)->pluck('encrypt_id');
    }

    // 根据唯一ID获取商家邮箱
    public function getEncryptIdByEmail($encrypt_id)
    {
        return Angel::where('encrypt_id', $encrypt_id)->pluck('email');
    }

    // 获取个人信息
    public function getAngelInfo($id)
    {
        $dt = AngelInfo::where('angel_id', $id)->first();
        if ($dt) {
            $dt = $dt->toArray();
        }

        return $dt;
    }

    // 商家账户邮件验证信息有效性
    public function createAngelValidatorStatus($encrypt_id)
    {
        $res = array(
            'suc' => 'success'
        );

        $email = AngelValidator::where('encrypt_id', $encrypt_id)->first();
        if (empty($email)) {
            $res['suc'] = 'error';
            $res['msg'] = '注册账号已失效, 请重新注册!';
        } else {
            // 24小时有效激活时间
            if ((time() - strtotime($email->created_at)) > 60 * 60 * 24) {
                $res['suc'] = 'error';
                $res['msg'] = '已超注册账号激活时间,请重新注册!';
            }
        }

        if ($res['suc'] == 'success') {
            if (Angel::where('email', $email->email)->pluck('id')) {
                $res['suc'] = 'error';
                $res['msg'] = '注册账号已被注册使用,请重新选择邮箱注册!';
            } else {
                $res['email'] = $email->email;
                $res['refer'] = $email->refer;
            }
        }

        return $res;
    }

    // 注册商家Root用户
    public function createAngelRoot($data)
    {
        $res = true;
        DB::beginTransaction();

        $angel = new Angel();
        $angel->email = $data['email'];
        // $angel->refer = $data['refer'];
        $angel->encrypt_id = sha1($data['email'] . md5(time()));
        $angel->password = Hash::make($data['password']);

        $territory = new Territory();
        $territory->encrypt_id = sha1($data['email'] . md5(str_random(5) . time()));

        if (! $angel->save() || ! $territory->save()) {
            $res = false;
        } else {
            $ai = new AngelInfo();
            $ai->angel_id = $angel->id;
            $ai->refer = $data['refer'];

            $at = new AngelTerritory();
            $at->angel_id = $angel->id;
            $at->territory_id = $territory->id;

            $atg = new AngelTerritoryGrade();
            $atg->angel_id = $angel->id;
            $atg->territory_id = $territory->id;
            $atg->grade = 'root';

            if (! $ai->save() || ! $at->save() || ! $atg->save()) {
                $res = false;
            } elseif (! AngelValidator::where('email', $data['email'])->delete()) {
                $res = false;
            }
        }

        if ($res) {
            DB::commit();

            return $angel->id;
        } else {
            DB::rollBack();

            return $res;
        }
    }

    // 修改个人信息
    public function updateAccount($data, $id)
    {
        $res = true;
        DB::beginTransaction();

        if ($data['password'] != '') {
            $angel = Angel::find($id);
            $angel->password = Hash::make($data['password']);
            if (! $angel->save()) {
                $res = false;
            }
        }

        if ($res) {
            if ($up_id = AngelInfo::where('angel_id', $id)->pluck('id')) {
                $ai = AngelInfo::find($up_id);
            } else {
                $ai = new AngelInfo();
                $ai->angel_id = $id;
            }

            $ai->name = ! empty($data['name']) ? $data['name'] : NULL;
            $ai->birthday = ! empty($data['birthday']) ? $data['birthday'] : NULL;
            $ai->gender = $data['gender'];
            if (! $ai->save()) {
                $res = false;
            }
        }

        if ($res) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 修改个人头像
    public function updateAccountHead($head, $id)
    {
        if ($up_id = AngelInfo::where('angel_id', $id)->pluck('id')) {
            $ai = AngelInfo::find($up_id);
        } else {
            $ai = new AngelInfo();
            $ai->angel_id = $id;
        }

        $ai->head = $head;
        $ai->save();
    }

    // 修改商家密码
    public function editAngelPwd($email, $password, $encrypt_id)
    {
        $id = Angel::where('encrypt_id', $encrypt_id)->pluck('id');
        $angel = Angel::find($id);

        $angel->encrypt_id = sha1($email . md5(time()));
        $angel->password = Hash::make($password);

        if ($angel->save()) {
            return true;
        } else {
            return fasle;
        }
    }
}
