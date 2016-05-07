<?php
namespace Ecdo\EcdoMember;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\EcdoSpiderMan\SiteCommon;
use Ecdo\EcdoIronMan\CouponsUtils;
use Ecdo\EcdoHulk\WechatMemberUtils;

/**
 * 会员前台控制器
 * 
 * @category yunke
 * @package atlas\hell\mamber\src\controllers\site
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class MemberSite extends SiteCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->guid = parent::getSessionGuid();
        $this->openid = parent::getSessionOpenid();
    }

    // 会员中心
    // https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf32d12abd8f9185c&redirect_uri=http%3A%2F%2Fcloud.yunque.me%2Fa42178e4%2Fmember%2Fcenter&response_type=code&scope=snsapi_userinfo&state=&component_appid=wx1d5be9275107e81d
    public function center($guid)
    {
        $wcb = new \App\Lib\WechatCallBack([
            'appid' => \Config::get('key.wechat.appid'),
            'appsecret' => \Config::get('key.wechat.appsecret'),
            'token' => \Config::get('key.wechat.token'),
            'encodingAesKey' => \Config::get('key.wechat.encodingAesKey'),
            'guid' => $guid,
            'open' => true
        ]);

        $result = $wcb->oauth2Component(Input::all());
        if (! isset($result['openid']) && empty($this->openid)) {
            exit('<h1>openid Calibration error!</h1>');
        } else {
            if (empty($this->openid)) {
                parent::setSessionOpenid($result['openid']);
            }
        }

        $wmu = new WechatMemberUtils();
        $member = $wmu->getOneMemberByOpenID($this->openid);
        
        return View::make('EcdoMember::site/center')->with(compact('member'));
    }

    // 会员详情
    public function info($guid, $openid = '')
    {
        $wmu = new WechatMemberUtils();
        $member = $wmu->getOneMemberByOpenID($this->openid);

        return View::make('EcdoMember::site/info')->with(compact('member'));
    }

    // 卡券列表
    public function card($guid, $openid = '')
    {
        $cu = new \Ecdo\EcdoIronMan\CouponsSite();

        echo $cu->codePage($guid);
    }
}
