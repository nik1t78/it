<?php

class iCalendar extends CWidget
{
    public $iblock_id = 0;
    public $section_id = 0;
    
    public function run()
    {
        //Set locale
        if (isset(Yii::app()->params['calendarLocale']) && Yii::app()->params['calendarLocale'] == 'Russian') {
            $locale = 'ru_RU.utf8';
            $setlocale = setlocale(LC_ALL, $locale);
        }
        
        // Previous month and next month
        if (!empty($_GET['time'])) {
            $month = date('n'. $_GET['time']);
            $year = date('Y'.$_GET['time']);
            if (!empty($_GET['pnc']) && $_GET['pnc'] == 'n') $month++;
            if (!empty($_GET['pnc']) && $_GET['pnc'] == 'p') $month--;
        } else {
            $month = date('n');
            $year = date('Y');
        }
        
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $firstDayNextMonth = mktime(0, 0, 0, $month+1, 1, $year);
        
        $pnc = array('&lt;'=>CHtml::normalizeUrl(array('iblock/month', 'time'=>$firstDay, 'pnc'=>'p')),
                     '&gt;'=>CHtml::normalizeUrl(array('iblock/month', 'time'=>$firstDay, 'pnc'=>'n')));
        
        //Today
        $days = array();
        if ($firstDay <= time() && time() < $firstDayNextMonth) {
            $today = date('j', time());
            $days[$today] = array(NULL, NULL, '<span id="today">'.$today.'</span>');
        }
        
        //Make the links
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id AND iblock_section_id=:section_id AND timestamp_x > :time1 AND timestamp_x < :time2';
        $criteria->params = array(
            ':iblock_id' => $this->iblock_id,
            ':section_id' => $this->section_id,
            ':time1' => date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)),
            ':time2' => date("Y-m-d H:i:s", mktime(0, 0, 0, $month+1, 1, $year)),
        );
        $materials = IBlockElement::model()->active()->findAll($criteria);
        
        foreach ($materials as $element) {
            $days[date('j', strtotime($element->timestamp_x))] = array(CHtml::normalizeUrl(array('iblock/month', 'time'=>strtotime($element->timestamp_x))), 'linked-day');
        }
        
        if (isset($locale) && $locale == 'ru_RU.utf8') $len = 3;
        else $len = 2;
        
        $this->render('icalendar', array('year'=>$year, 'month'=>$month, 'days'=>$days, 'len'=>$len, 'url'=>'', 'pnc'=>$pnc));
    }
}