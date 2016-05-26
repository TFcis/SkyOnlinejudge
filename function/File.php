<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function file_read($path)
{
    global $_E;
    $file = fopen($path, 'r');
    if ($file === false) {
        LOG::msg(Level::Warning, "cannot open the file $path");

        return false;
    } else {
        $data = '';
        //$data=fpassthru($file);
        //echo "<p>".$data;
        while ($buffer = fread($file, 40)) {
            $data = $data.$buffer;
            //echo "<p> buffer=".$buffer." ";
            //echo "     data=".$data;
        }
        fclose($file);
		
        return $data;
    }
}

function file_create($path, $txt)
{
    global $_E;
    $file = @fopen($path, 'x');
    if ($file === false) {
        LOG::msg(Level::Warning, "cannot open the file $path");

        return false;
    } else {
        fwrite($file, $txt);
    }
    fclose($file);
}