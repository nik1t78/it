<?php

/*
 * Класс с разными мелкими помощьниками.
 */

class Misc {
    public static $divider = '--';
    
    /**
     * Формирование массива для многоуровнего выпадающего списка
     * @param type $inArr многомерный массив вида:
     * array(
     *  id => array(
     *      'name' => 'Name',
     *      'items' => array(
     *          id => array(...)
     *      ),
     *  ), ...
     * );
     */
    static public function formatMultiList($inArr, $divider=''){
        $result = array();
        foreach($inArr as $id => $item){
            $result[$id] = $divider.' '.$item['name'];
            if(isset($item['items']) && !empty($item['items'])){
                $items = self::formatMultiList($item['items'], $divider.self::$divider);
                $result += $items;
            }
        }
        return $result;
    }
    
    /**
     * Замена пути от файлового менеджера на относительный путь
     * @param string $filePath исходный путь к файлу
     * @return string
     */
    public static function normalizeFilePath($filePath)
    {
        // Правим путь к файлу
        $filePath = str_replace('/php/../', '/', $filePath);
        
        return $filePath;
    }
    
    
    public static function translit($str) 
    {
        
        $tr = array(
            'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g',
            'Д' => 'd', 'Е' => 'e', 'Ё' => 'jo', 'Ж' => 'zh',
            'З' => 'z', 'И' => 'i', 'Й' => 'j', 'К' => 'k',
            'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p',
            'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
            'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch',
            'Ш' => 'sh', 'Щ' => 'sch', 'Ы' => 'ji', 'Ь' => '', 'Ъ' => '',
            'Э' => 'je', 'Ю' => 'ju', 'Я' => 'ja', 
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g',
            'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ы' => 'ji', 'ь' => '', 'ъ' => '',
            'э' => 'je', 'ю' => 'ju', 'я' => 'ja', 
            " " => "_", "." => "", "," => "", "/" => "_"
        );
        
        if(preg_match('/[^A-Za-z0-9_\-]/', $str)){
            $urlstr = strtr($str, $tr);
            $str = preg_replace('/[^A-Za-z0-9_]/', '', $urlstr);
        }
        
        return $str;
    }
}
?>
