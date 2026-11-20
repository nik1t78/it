<?php


/*
 * Класс для работы с датами.
 */
class DateFormat{
    public static $dbFormatWithTime = 'Y-m-d H:i:s';
    public static $dbFormat = 'Y-m-d';
    public static $compact = 'd.m.Y';
    public static $compactWithTime = 'd.m.Y H:i:s';
    public static $full = 'd F Y';
    public static $fullWithTime = 'd F Y H:i:s';
    public static $compareDate = '%H:%I:%S';

    /*
     * Конвертирование даты из строкового формата в формат базы данных
     */
    public static function convertStrToDb($inDate, $format = '', $dbFormat = ''){
        $format = empty($format) ? self::$compactWithTime : $format;
        $dbFormat = empty($dbFormat) ? self::$dbFormatWithTime : $dbFormat;

        $date = DateTime::createFromFormat($format, $inDate);
        $result = $date->format($dbFormat);

        return $result;
    }

    /*
     * Конвертирование даты из формата базы данных в строковой формат
     */
    public static function convertDbToStr($inDate, $format = '', $dbFormat = '', $dateLength = 'long', $timeLength = 'short'){
        $format = empty($format) ? self::$compactWithTime : $format;
        $dbFormat = empty($dbFormat) ? self::$dbFormatWithTime : $dbFormat;

        $date = DateTime::createFromFormat($dbFormat, $inDate);
        $test = new CDateFormatter('ru');
        $result = $test->formatDateTime($date->format($format), $dateLength, $timeLength);
        return $result;
    }

    public static function convertFormat($inDate, $format = 'dd MMMM yyyy HH:mm:ss')
    {
        $date = new CDateFormatter('ru');
        $result = $date->format($format, strtotime($inDate));
        return $result;
    }

    /*
     * Сравнение двух дат и вывод результата в необходимом формате
     */
    public static function compareDate($date1, $date2, $format = ''){
        $format = empty($format) ? self::$compareDate : $format;

        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);

        return $interval->format($format);
    }

    /*
     * Вхождение даты в указанный диапазон
     * $start - начало диапазона
     * $finish - конец диапазона
     * $needle - искомая дата
     * $incExt - true - включать крайние значения, false - вхождение между крайними значениями
     */
    public static function outOfRange($start, $finish, $needle, $incExt = true){
        $date1 = strtotime($start);
        $date2 = strtotime($finish);
        $date3 = strtotime($needle);

        if($incExt){
            if($date3 >= $date1 && $date3 <= $date2) return true;
        }else{
            if($date3 > $date1 && $date3 < $date2) return true;
        }

        return false;
    }

    public static function is_date($string)
    {
        if (strlen($string) < 6)
            return false;

        $convVal = strtotime($string);
        if (!$convVal) return false;
        //VarDumper::dump(date('Y-m-d H:i:s', $string));
        //if (date('Y-m-d H:i:s', $convVal) != $string)
        //        return false;
        return $convVal;
    }

    public static function dateIsNull($date)
    {
        if (strtotime($date) < 0 || is_null($date))
            return true;
        return false;
    }
}
?>
