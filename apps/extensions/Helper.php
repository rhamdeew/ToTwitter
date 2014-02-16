<?php
/**
 * Created by PhpStorm.
 * User: rail
 * Date: 16.02.14
 * Time: 22:52
 */

class Helper {
    public static function Dump($data,$title='',$var_dump=0,$hide=0) {
        if($hide==1) echo "<div style='display:none'>";
        if(!empty($title)) echo "<h3>".$title."</h3>";
        echo "<pre>";
        if($var_dump==1) var_dump($data);
        else print_r($data);
        echo "</pre>";
        if($hide==1) echo "</div>";
    }
} 