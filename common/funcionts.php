<?php
namespace qimao\common;

class funcionts{
    static public function saveFile($path,$fileName,$data,$isAppend = false)
    {
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        $name = $path.'/'.$fileName;
        if ($isAppend){
            file_put_contents($name,$data,FILE_APPEND);
        }else{
            file_put_contents($name,$data);
        }
    }
}
