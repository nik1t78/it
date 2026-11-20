<?php

class VarDumper extends CVarDumper{
    
    public static function dump($var, $depth=10, $return=false, $highlight=true){
        if($return)
            return self::dumpAsString($var, $depth, $highlight);
        else
            echo self::dumpAsString($var, $depth, $highlight);
    }
}