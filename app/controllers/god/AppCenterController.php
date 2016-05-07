<?php namespace Ecdo\God;

use Ecdo\Universe\Atlas\AtlasManager;
use Ecdo\Model\Universe\UniverseStar;
use Ecdo\Model\Universe\Ecdo\Model\Universe;
/**
 * 平台应用中心控制器
 * 
 * @package \Ecdo\God
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class AppCenterController extends \Controller
{
    /**
     * 应用中心首页
     */
    public function index()
    {
        $am = new AtlasManager();
        $stars = $am->getAllStars();
        
        $baseStars = [];
        $optStars = [];
        foreach ((array)$stars as $key => $star) {
            if ($star['type'] === 'base') {
                $baseStars[$key] = $star;
            } else {
                $optStars[$key] = $star;
            }
        }
        
        return \View::make('god/appcenter/index', compact('stars', 'baseStars', 'optStars'));
    }
    
    /**
     * 显示传递的应用的信息
     * 
     */
    public function show()
    {
        $am = new AtlasManager();
        $star = $am->getStar(\Input::get('star'));
        return \View::make('god/appcenter/show', compact('star'));
    }
    
    /**
     * 修改传递的应用的信息
     * ajax方式，输出success成功，failed失败
     * 
     * @return bool
     */
    public function edit()
    {
        $star = \Input::get('star');
        
        if (empty($star['id'])) {
            $mdlStar = new UniverseStar($star);
            $rs = $mdlStar->save();
        } else {
            $rs = UniverseStar::where('id', $star['id'])->update($star);
        }
        
        if ($rs) {
            $am = new AtlasManager();
            $am->updateCache();
            $rtn = 'success';
        } else {
            $rtn = 'failed';
        }
        
        return $rtn;
    }
}
