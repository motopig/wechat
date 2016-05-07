<?php
namespace Ecdo\EcdoSpiderMan;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Lib\RouteCommon;
use Session;

/**
 * 商家前台控制器公用类
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class SiteCommon extends Controller
{
    public $params;

	public function __construct()
    {
        self::route();
        self::isPostGuid();
    }

    public function route()
    {
    	$rc = new RouteCommon();

    	return $this->params = $rc->route();
    }

    public function isPostGuid()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && Input::get('csrf_guid')) {
            if (! Input::get('csrf_guid') || Input::get('csrf_guid') != Session::get('guid')) {
                if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && 
                    strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
                    $ajax['errmsg'] = '云号不存在或已失效，请重新操作';
                    $ajax['errcode'] = 'error';
                    exit(json_encode($ajax, JSON_UNESCAPED_UNICODE));
                } else {
                    $post = '云号不存在或已失效，请重新操作';
                    echo "<script>alert('" . $post . "');</script>";
                    header("Content-type: text/html; charset=utf-8");
                    echo "<meta http-equiv=refresh content='0; url=" . 
                    action('\Ecdo\EcdoSpiderMan\AngelDashboard@index') . "'>";  
                    exit(0);
                }
            }
        }
    }

    // 获取session guid
    public function getSessionGuid()
    {
        return Session::get('guid');
    }

    // 获取session openid
    public function getSessionOpenid()
    {
        return Session::get('openid');
    }

    // 储存session openid
    public function setSessionOpenid($openid)
    {
        Session::put('openid', $openid);
    }

    // 用户session验证
    public function isSessionOpenid()
    {
        if (! Session::get('openid')) {
            exit('<h1>Invalid permissions!</h1>');
        }
    }
}
