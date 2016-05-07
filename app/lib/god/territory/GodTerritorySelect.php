<?php
namespace App\Lib;

use App\Models\Territory;
use Ecdo\EcdoSpiderMan\Tower;

/**
 * 企业数据获取类
 *
 * @category yunke
 * @package app\lib\god\territory
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class GodTerritorySelect
{
    // 获取所有企业
    public function getTerritoryAll()
    {
        $dt = Territory::select('*')->with('tower')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->toArray();
        
        return $dt;
    }
}
