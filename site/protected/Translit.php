<?php

/*
 * Класс для преобразования русских букв и символов в безопасные url символы
 */
class Translit{
    /**
    public static $dbFormat = 'Y-m-d H:i:s';
    public static $compact = 'd.m.Y';
    public static $compactWithTime = 'd.m.Y H:i:s';
    public static $full = 'd F Y';
    public static $fullWithTime = 'd F Y H:i:s';
    public static $compareDate = '%H:%I:%S';
     * 
     */
    
    public static function ru2eng($in, $isFileName=true){
                
        if(preg_match('/[^A-Za-z0-9_\-.]/', $in)){
            $out = self::translitIt($in);
            $out = preg_replace('/[^A-Za-z0-9_\-.]/', '', $out);
        }else{
            $out = $in;
        }
                
        return $out;
    }
    
    private static function translitIt($str){
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
            " "=> "_", "/"=> "_"
        );
        return strtr($str, $tr);
    }
}
?>
