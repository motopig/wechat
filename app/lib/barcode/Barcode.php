<?php

/**
 * 条形码生成器 - no
 */
class Barcode
{

    private static $codeTypes = array(
        'code128', 
        'code39', 
        'code25', 
        'codabar'
    );

    public static function png($barcode, $size, $isHorizontal)
    {
        $image = self::genBarcode($barcode, $size, $isHorizontal);

        Header("Content-type: image/png");
        ImagePng($image);
        ImageDestroy($image);
    }

    public static function genBarcode($text, $size = 20, $isHorizontal = true, $codeType = 'code128')
    {
        $codeType = strtolower($codeType);
        if (! in_array($codeType, self::$codeTypes)) {
            echo 'Code Type is not available.';
            exit();
        }

        $type = ucfirst($codeType);
        $mthd = 'gen' . $type;
        $strCode = self::$mthd($text);

        // Pad the edges of the barcode
        $codeLen = 20;
        for ($i = 1; $i <= strlen($strCode); $i ++) {
            $codeLen = $codeLen + (int) (substr($strCode, ($i - 1), 1));
        }

        if ($isHorizontal) {
            $imgWidth = $codeLen;
            $imgHeight = $size;
        } else {
            $imgWidth = $size;
            $imgHeight = $codeLen;
        }
        
        $image = imagecreate($imgWidth, $imgHeight);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        
        imagefill($image, 0, 0, $white);
        
        $location = 10;
        for ($position = 1; $position <= strlen($strCode); $position ++) {
            $curSize = $location + (substr($strCode, ($position - 1), 1));
            if ($isHorizontal) {
                imagefilledrectangle($image, $location, 0, $curSize, $imgHeight, 
                    ($position % 2 == 0 ? $white : $black));
            } else {
                imagefilledrectangle($image, 0, $location, $imgWidth, $curSize, 
                    ($position % 2 == 0 ? $white : $black));
            }
            $location = $curSize;
        }
        
        return $image;
    }

    /**
     * code128
     *
     * @param string $text            
     * @return string
     */
    private static function genCode128($text)
    {
        $codeString = '';
        $chksum = 104;
        // Must not change order of array elements as the checksum depends on the array's key to validate final code
        $codeArray = array(
            ' ' => '212222', 
            '!' => '222122', 
            '"' => '222221', 
            '#' => '121223', 
            '$' => '121322', 
            '%' => '131222', 
            '&' => '122213', 
            '\'' => '122312', 
            '(' => '132212', 
            ')' => '221213', 
            '*' => '221312', 
            '+' => '231212', 
            ',' => '112232', 
            '-' => '122132', 
            '.' => '122231', 
            '/' => '113222', 
            '0' => '123122', 
            '1' => '123221', 
            '2' => '223211', 
            '3' => '221132', 
            '4' => '221231', 
            '5' => '213212', 
            '6' => '223112', 
            '7' => '312131', 
            '8' => '311222', 
            '9' => '321122', 
            ':' => '321221', 
            ';' => '312212', 
            '<' => '322112', 
            '=' => '322211', 
            '>' => '212123', 
            '?' => '212321', 
            '@' => '232121', 
            'A' => '111323', 
            'B' => '131123', 
            'C' => '131321', 
            'D' => '112313', 
            'E' => '132113', 
            'F' => '132311', 
            'G' => '211313', 
            'H' => '231113', 
            'I' => '231311', 
            'J' => '112133', 
            'K' => '112331', 
            'L' => '132131', 
            'M' => '113123', 
            'N' => '113321', 
            'O' => '133121', 
            'P' => '313121', 
            'Q' => '211331', 
            'R' => '231131', 
            'S' => '213113', 
            'T' => '213311', 
            'U' => '213131', 
            'V' => '311123', 
            'W' => '311321', 
            'X' => '331121', 
            'Y' => '312113', 
            'Z' => '312311', 
            '[' => '332111', 
            '\\' => '314111', 
            ']' => '221411', 
            '^' => '431111', 
            '_' => '111224', 
            '`' => '111422', 
            'a' => '121124', 
            'b' => '121421', 
            'c' => '141122', 
            'd' => '141221', 
            'e' => '112214', 
            'f' => '112412', 
            'g' => '122114', 
            'h' => '122411', 
            'i' => '142112', 
            'j' => '142211', 
            'k' => '241211', 
            'l' => '221114', 
            'm' => '413111', 
            'n' => '241112', 
            'o' => '134111', 
            'p' => '111242', 
            'q' => '121142', 
            'r' => '121241', 
            's' => '114212', 
            't' => '124112', 
            'u' => '124211', 
            'v' => '411212', 
            'w' => '421112', 
            'x' => '421211', 
            'y' => '212141', 
            'z' => '214121', 
            '{' => '412121', 
            '|' => '111143', 
            '}' => '111341', 
            '~' => '131141', 
            'DEL' => '114113', 
            'FNC 3' => '114311', 
            'FNC 2' => '411113', 
            'SHIFT' => '411311', 
            'CODE C' => '113141', 
            'FNC 4' => '114131', 
            'CODE A' => '311141', 
            'FNC 1' => '411131', 
            'Start A' => '211412', 
            'Start B' => '211214', 
            'Start C' => '211232', 
            'Stop' => '2331112'
        );
        $codeKeys = array_keys($codeArray);
        $codeValues = array_flip($codeKeys);
        for ($i = 1; $i <= strlen($text); $i ++) {
            $activeKey = substr($text, ($i - 1), 1);
            $codeString .= $codeArray[$activeKey];
            $chksum = ($chksum + ($codeValues[$activeKey] * $i));
        }
        $codeString .= $codeArray[$codeKeys[($chksum -
             (intval($chksum / 103) * 103))]];
        
        $codeString = '211214' . $codeString . '2331112';
        return $codeString;
    }

    /**
     * code39
     *
     * @param string $text            
     * @return string
     */
    private static function genCode39($text)
    {
        $codeString = '';
        $codeArray = array(
            '0' => '111221211', 
            '1' => '211211112', 
            '2' => '112211112', 
            '3' => '212211111', 
            '4' => '111221112', 
            '5' => '211221111', 
            '6' => '112221111', 
            '7' => '111211212', 
            '8' => '211211211', 
            '9' => '112211211', 
            'A' => '211112112', 
            'B' => '112112112', 
            'C' => '212112111', 
            'D' => '111122112', 
            'E' => '211122111', 
            'F' => '112122111', 
            'G' => '111112212', 
            'H' => '211112211', 
            'I' => '112112211', 
            'J' => '111122211', 
            'K' => '211111122', 
            'L' => '112111122', 
            'M' => '212111121', 
            'N' => '111121122', 
            'O' => '211121121', 
            'P' => '112121121', 
            'Q' => '111111222', 
            'R' => '211111221', 
            'S' => '112111221', 
            'T' => '111121221', 
            'U' => '221111112', 
            'V' => '122111112', 
            'W' => '222111111', 
            'X' => '121121112', 
            'Y' => '221121111', 
            'Z' => '122121111', 
            '-' => '121111212', 
            '.' => '221111211', 
            ' ' => '122111211', 
            '$' => '121212111', 
            '/' => '121211121', 
            '+' => '121112121', 
            '%' => '111212121', 
            '*' => '121121211'
        );
        
        // Convert to uppercase
        $upperText = strtoupper($text);
        
        for ($i = 1; $i <= strlen($upperText); $i ++) {
            $codeString .= $codeArray[substr($upperText, ($i - 1), 1)] . '1';
        }
        
        $codeString = '1211212111' . $codeString . '121121211';
        
        return $codeString;
    }

    /**
     * code25
     *
     * @param string $text            
     * @return string
     */
    private static function genCode25($text)
    {
        $codeString = '';
        $codeArray1 = array(
            '1', 
            '2', 
            '3', 
            '4', 
            '5', 
            '6', 
            '7', 
            '8', 
            '9', 
            '0'
        );
        $codeArray2 = array(
            '3-1-1-1-3', 
            '1-3-1-1-3', 
            '3-3-1-1-1', 
            '1-1-3-1-3', 
            '3-1-3-1-1', 
            '1-3-3-1-1', 
            '1-1-1-3-3', 
            '3-1-1-3-1', 
            '1-3-1-3-1', 
            '1-1-3-3-1'
        );
        
        for ($i = 1; $i <= strlen($text); $i ++) {
            for ($Y = 0; $Y < count($codeArray1); $Y ++) {
                if (substr($text, ($i - 1), 1) == $codeArray1[$Y]) {
                    $temp[$i] = $codeArray2[$Y];
                }
            }
        }
        
        for ($i = 1; $i <= strlen($text); $i += 2) {
            if (isset($temp[$i]) && isset($temp[($i + 1)])) {
                $temp1 = explode('-', $temp[$i]);
                $temp2 = explode('-', $temp[($i + 1)]);
                for ($Y = 0; $Y < count($temp1); $Y ++) {
                    $codeString .= $temp1[$Y] . $temp2[$Y];
                }
            }
        }
        
        $codeString = '1111' . $codeString . '311';
        
        return $codeString;
    }

    /**
     * codabar
     *
     * @param string $text            
     * @return string
     */
    private static function genCodabar($text)
    {
        $codeString = '';
        $codeArray1 = array(
            '1', 
            '2', 
            '3', 
            '4', 
            '5', 
            '6', 
            '7', 
            '8', 
            '9', 
            '0', 
            '-', 
            '$', 
            ':', 
            '/', 
            '.', 
            '+', 
            'A', 
            'B', 
            'C', 
            'D'
        );
        $codeArray2 = array(
            '1111221', 
            '1112112', 
            '2211111', 
            '1121121', 
            '2111121', 
            '1211112', 
            '1211211', 
            '1221111', 
            '2112111', 
            '1111122', 
            '1112211', 
            '1122111', 
            '2111212', 
            '2121112', 
            '2121211', 
            '1121212', 
            '1122121', 
            '1212112', 
            '1112122', 
            '1112221'
        );
        
        // Convert to uppercase
        $upper_text = strtoupper($text);
        
        for ($i = 1; $i <= strlen($upper_text); $i ++) {
            for ($Y = 0; $Y < count($codeArray1); $Y ++) {
                if (substr($upper_text, ($i - 1), 1) == $codeArray1[$Y]) {
                    $codeString .= $codeArray2[$Y] . '1';
                }
            }
        }
        $codeString = '11221211' . $codeString . '1122121';
        
        return $codeString;
    }
}
