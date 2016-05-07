<?php
namespace Ecdo\EcdoThor;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoThor\ThorCommon;
use Ecdo\EcdoThor\LuckDrawUtils;

/**
 * 门店基本配置
 *
 * @category yunke
 * @package atlas\hell\bat-man\src\controllers\entityshop
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class LuckDraws extends ThorCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->ldu = new LuckDrawUtils();
    }
    
    // 抽奖列表
    public function index()
    {
        $luckdraw = $this->ldu->getLuckDrawPage();
        
        return View::make('EcdoThor::luckdraw/index')->with(compact('luckdraw'));
    }

    // 抽奖搜索
    public function luckdrawSearch()
    {
        $search = Input::get('search');
        $luckdraw = $this->ldu->getLuckDrawSearchPage($search);

        return View::make('EcdoThor::luckdraw/index')->with(compact('luckdraw', 'search'));
    }

    // 创建抽奖活动
    public function luckdrawCreate()
    {
        $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
        $coupons = json_encode($cu->getEffectiveCoupons(), JSON_UNESCAPED_UNICODE);
        $type = json_encode($this->ldu->getType(), JSON_UNESCAPED_UNICODE);

        return View::make('EcdoThor::luckdraw/create')->with(compact('type', 'coupons'));
    }

    // 创建抽奖活动处理
    public function luckdrawCreateDis()
    {
        $data = Input::all();
        $prize = explode('@@@', $data['prize']);
        $data['type'] = explode(',', $prize[0]);
        $data['content'] = explode(',', $prize[1]);
        $data['chance'] = explode(',', $prize[2]);
        $data['quantity'] = explode(',', $prize[3]);

        $arr = $this->ldu->luckdrawCreate($data);
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoThor\LuckDraws@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑抽奖活动
    public function luckdrawUpdate()
    {
        $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
        $_coupons = $cu->getEffectiveCoupons();
        $_type = $this->ldu->getType();
        $coupons = json_encode($_coupons, JSON_UNESCAPED_UNICODE);
        $type = json_encode($_type, JSON_UNESCAPED_UNICODE);
        $luckdraw = $this->ldu->getLuckDrawOne(Input::get('id'));
        $id = Input::get('id');

        return View::make('EcdoThor::luckdraw/update')->with(compact('_type', '_coupons', 'type', 'coupons', 'luckdraw', 'id'));
    }

    // 编辑抽奖活动处理
    public function luckdrawUpdateDis()
    {
        $data = Input::all();
        $prize = explode('@@@', $data['prize']);
        $data['type'] = explode(',', $prize[0]);
        $data['content'] = explode(',', $prize[1]);
        $data['chance'] = explode(',', $prize[2]);
        $data['quantity'] = explode(',', $prize[3]);
        $data['pid'] = explode(',', $prize[4]);

        $arr = $this->ldu->luckdrawUpdate($data);
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoThor\LuckDraws@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除抽奖活动
    public function luckdrawDelete()
    {
        $arr = $this->ldu->luckdrawDelete(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoThor\LuckDraws@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
