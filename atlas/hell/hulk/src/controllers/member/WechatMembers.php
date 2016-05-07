<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatMemberUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 商家微信图文
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\member
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMembers extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wmu = new WechatMemberUtils();
    }
    
    // 会员列表
    public function index()
    {
        $member = $this->wmu->getMemberPage();

        return View::make('EcdoHulk::member/index')->with(compact('member'));
    }

    // 查看会员
    public function shMember()
    {
    	$member = $this->wmu->getOneMember(Input::get('member_id'));

    	return View::make('EcdoHulk::member/show')->with(compact('member'));
    }

    // 搜索会员
    public function seMember()
    {
    	$search = Input::get('search');
        $member = $this->wmu->getSearchMemberPage($search);

        return View::make('EcdoHulk::member/index')->with(compact('member', 'search'));
    }

    // 筛选会员
    public function fiMember()
    {
    	$group = $this->wmu->getGroup();

        return View::make('EcdoHulk::member/filter')->with(compact('group'));
    }

    // 筛选会员处理
    public function fiMemberDis()
    {
        // 判断是否已经筛选进分页
        if (Input::get('filter')) {
            // 删除空元素
            $data = Input::get('filter');
            $data = array_filter($data);
        } else {
            // 删除csrf_token csrf_guid 空元素
            $data = Input::All();
            unset($data['csrf_token']);
            unset($data['csrf_guid']);
            $data = array_filter($data);
        }

        $filter = $data;
        $member = $this->wmu->getFilterMemberPage($filter);
        
        return View::make('EcdoHulk::member/index')->with(compact('member', 'filter'));
    }
}
