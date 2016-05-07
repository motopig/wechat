<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 商家微信高级图文
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\material
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMaterial extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // 高级图文列表
    public function index()
    {
        return View::make('EcdoHulk::material/index');
    }

    // 创建高级单图文
    public function crSingel()
    {
        return View::make('EcdoHulk::material/create_singel');
    }

    // 创建高级多图文
    public function crMany()
    {
        return View::make('EcdoHulk::material/create_many');
    }
}
