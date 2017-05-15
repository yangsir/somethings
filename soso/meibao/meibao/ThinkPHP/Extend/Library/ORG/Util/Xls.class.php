<?php
/**
 * xls文件导出类
 */
class Xls {

    private $title='啥玩意儿';

    function __construct()
    {

    }


    function printData($heard=array(),$data=array(),$title=false)
    {
        if($title != false){
            $this->title=iconv('UTF-8', 'GBK', $title);
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=".$this->title.".xls ");
        header("Content-Transfer-Encoding: binary ");
        $this->xlsBOF();


        for($i=0;$i<count($heard);$i++){
            $this->xlsWriteLabel(0,$i,$heard[$i]);
        }

        for($x=1;$x<count($data)+1;$x++){
            $arrayKey=array_keys($data[$x-1]);

            for($s=0;$s<count($arrayKey);$s++){
                $this->xlsWriteLabel($x,$s,$data[$x-1][$arrayKey[$s]]);
            }
        }
    }

    function __destruct()
    {
        $this->xlsEOF();
    }

    function xlsBOF() {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }

    function xlsEOF() {
        echo pack("ss", 0x0A, 0x00);
        return;
    }

    function xlsWriteNumber($Row, $Col, $Value) {
        $Value=iconv('UTF-8', 'GBK', $Value);
        echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        echo pack("d", $Value);
        return;
    }

    function xlsWriteLabel($Row, $Col, $Value ) {
        $Value=iconv('UTF-8', 'GBK', $Value);
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
        return;
    }

}
