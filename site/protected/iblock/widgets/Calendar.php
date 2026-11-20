<?php

Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class Calendar extends CWidget
{
    /**
     * @var type integer Iblock id
     */
    public $iblock_id = 0;
    /**
     *
     * @var type integer Section of iblock id
     */
    public $section_id = 0;
    /**
     * @var type integer Количество прошлых годов, выводимых помимо текущего
     */
    public $lastYears = 1; 
    
    private $_iblock;
        
    
    public function init() 
    {
        
    }
    
    public function run()
    {
        $year = date('Y');
        $iblock = IBlock::model()->findByPk($this->iblock_id);
        if($iblock === null) 
            throw new CHttpException(404, Yii::t('iblock', 'Страница не найдена'));
        $this->_iblock = $iblock;
                
        setlocale(LC_TIME, 'ru_RU.UTF-8');
        //echo($this->generate_calendar($year, 6, array(), 4, NULL, 1, array()));
        
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id AND iblock_section_id=:section_id AND active_from > :time1';
        $criteria->params = array(
            ':iblock_id' => $this->iblock_id,
            ':section_id' => $this->section_id,
            ':time1' => date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year - $this->lastYears))
        );
        $criteria->order = 'active_from ASC';
        
        $els = IBlockElement::model()->active()->findAll($criteria);
        
        $yearmonth = array();
        $count = 0;
        foreach ($els as $element) {
            $ym = date('F Y', strtotime($element->active_from));
            list($d, $m, $y) = explode('.', date('j.m.Y', strtotime($element->active_from)));
            
            $yearmonth[$ym][$d] = array(Yii::app()->createUrl('iblock/default/date', array('iblock'=>$iblock->code, 'date'=>$d.'.'.$m.'.'.$y)), 'linked-day');
            
        }
        //VarDumper::dump($yearmonth);
        $this->render('icalendar', array('calendar'=>$yearmonth));
    }
    
    public function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array(), $tblOptions=array())
    { 
        $first_of_month = gmmktime(0,0,0,$month,1,$year);
        #remember that mktime will automatically correct if invalid dates are entered
        # for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
        # this provides a built in "rounding" feature to generate_calendar()

        $day_names = array(); #generate all the day names according to the current locale
        for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) { #January 4, 1970 was a Sunday
            $day_names[$n] = DateFormat::convertFormat(date('d.m.Y', $t), 'ccc');
            //    $day_names[$n] = mb_convert_case(gmstrftime('%a',$t), MB_CASE_TITLE, 'UTF-8'); #%A means full textual day name
        }

        list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
        $weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
        //$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names
        $title = DateFormat::convertFormat('1.'.$month.'.'.$year, 'LLLL yy');
        
        #Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
        @list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
        if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
        if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
        $calendar = '<table cellpadding="0" cellspacing="0" id="calendar_body_'.$month.'_'.$year.'" title="'.$title.'" class="'.(isset($tblOptions['class']) ? $tblOptions['class'] : '').'">'."\n";

        if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
                #if day_name_length is >3, the full name of the day will be printed
                $calendar .= "<thead><tr>";
                foreach($day_names as $d)
                        $calendar .= '<th abbr="'.htmlspecialchars($d).'">'.htmlspecialchars($d).'</th>';
                $calendar .= "</tr></thead>\n";
        }
        $calendar .= "<tr>";
        if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
        for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
                if($weekday == 7){
                        $weekday   = 0; #start a new week
                        $calendar .= "</tr>\n<tr>";
                }
                
                if(isset($days[$day]) and is_array($days[$day])){
                        @list($link, $classes, $content) = $days[$day];
                        if(is_null($content))  $content  = $day;
                        $calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
                                ($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : '<span>'.$content.'</span>').'</td>';
                }
                else $calendar .= "<td class='".($weekday >= 5 ? 'weekend' : '')."'><span>".$day."</span></td>";
        }
        if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

        return $calendar."</tr>\n</table>\n";
    }
}
