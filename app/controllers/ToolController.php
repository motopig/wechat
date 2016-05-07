<?php
namespace App\Controllers;

use Illuminate\Support\Facades\View;



class ToolController extends BaseController
{
    function qrCode($url='',$size='8'){
        $url = rawurldecode($url);
        require_once app_path()."/lib/qrcode/phpqrcode.php";
        return \QRcode::png($url, false,'L',$size, 1);
    }

    function barCode($barcode, $size = '8', $isHorizontal = 'N')
    {
    	require_once app_path()."/lib/barcode/Barcode.php";
        return \Barcode::png($barcode, $size, $isHorizontal);
    }
}
