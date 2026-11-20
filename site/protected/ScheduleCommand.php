<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ScheduleCommand extends CConsoleCommand{
    private $curr_date;
    public function run($args){
        date_default_timezone_set('Europe/Moscow');
        $this->curr_date = date('Y-m-d H:i:s');
        //Получение всех невыполненных задач 
        $t = $this->getActiveTask();
        
        foreach($t as $task){ 
            //Выполняем задачу
            $taskResult = $this->executeCommand($task->command);
            
            print_r($taskResult);
            if(is_array($taskResult) && isset($taskResult[0])) $taskResult = $taskResult[0];
            //Пишем результаты выполнения в лог
            if(is_string($taskResult)){
	            Yii::log(Yii::t('cron', $taskResult), 'info', 'cron.task');
            }elseif(is_array($taskResult)){
	            foreach($taskResult as $tmpRes){
	                Yii::log(Yii::t('cron', 'Результат выполнения задачи #'.$task->id.': ').$tmpRes, 'info', 'cron.task');
	            }
            }
            
            
            $task->last_active = $this->curr_date;
            if($task->period == null) $task->complete = Schedule::COMPLETE;
            else $task->active_date = $this->parseCronStr($task->period);
            $task->save();
            
        }
    }
    
    public function parseCronStr($period){
        $tmp = explode(' ', $period);
        
        if(is_array($tmp) && count($tmp) == 5){ //Стандартный формат Cron - 5 полей
            $minutes = $tmp[0];
            $hours = $tmp[1];
            $days = $tmp[2];
            $months = $tmp[3];
            $dayOfWeek = $tmp[4];
            
            $taskDate = strtotime($this->curr_date);
            /*
            $tmp2 = array();
            foreach($tmp as $val){
                if($val == '*'){
                    $tmp2[] = 
                }
            }*/
            
            if($minutes == '*'){ //Выполнять каждую минуту
                $nextMin = date('i', $taskDate)+1;
            }else{// выполнять в определенную минуту
                $nextMin = $minutes;
            }
            if($hours == '*' && $minutes != '*'){ //Выполнить в следующий час
                $nextHour = date('H', $taskDate)+1;
            }elseif($minutes == '*'){ // выполнить в этот час
                $nextHour = date('H', $taskDate);
            }else{ //выполнить в определенный час
                $nextHour = $hours;
            }
            if($days == '*' && $hours != '*'){ //Выполнить в следующий день
                $nextDays = date('j', $taskDate)+1;
            }elseif($hours == '*'){ //выполнить в этот день
                $nextDays = date('j', $taskDate);
            }else{ //выполнить в определенный день
                $nextDays = $days;
            }
            if($months == '*' && $days != '*'){ //Выполнить в следующий месяц
                $nextMonth = date('n', $taskDate)+1;
            }elseif($days == '*'){ //выполнить в этот месяц
                $nextMonth = date('n', $taskDate);
            }else{//выполнить в определенный месяц
                $nextMonth = $months;
            }
            
            $nextTaskDate = date('Y-m-d H:i:s', mktime($nextHour, $nextMin, 0, $nextMonth, $nextDays));
        }else{
            return false;
        }
        
        return $nextTaskDate;
    }
    
    public function getActiveTask(){
        $criteria = new CDbCriteria;
        $criteria->condition = 'active_date <=:curr_date and complete=:status';
        $criteria->params = array(
            ':curr_date'=>$this->curr_date,
            ':status'=>Schedule::NONCOMPLETE
        );
        
        $tasks = Schedule::model()->findAll($criteria);
        
        return $tasks;
    }
    
    public function executeCommand($route){        
        /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Yii::app()->params['host'].$route);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//разрешаем редирект
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//указывает, что функция curl_exec должна вернуть полученный ответ, а не отправить его сразу браузеру
        $result = curl_exec($ch);
        curl_close($ch);*/
        
        $module = false;
        $model = false;
        $method = false;
        $params = false;
        $result = array();
        
        //ResultController::actionIndex();
        
        $tmp = explode('/', $route);
        
        
        foreach($tmp as $sec){
            if(empty($sec)) continue;
            
            if(!$module) {
                $module = Yii::app()->getModule($sec);
                if($module){
                    continue;
                }else{
                    return Yii::t('cron', 'Модуль "'.$sec.'" не найден.');
                }
                ///////////////////////////method_exists($sec, $method_name)                
            }elseif(!$model){
                $sec = ucfirst($sec);
                if(file_exists($module->getBasePath().'/models/'.$sec.'.php')){
                    $model = new $sec;
                    continue;
                }else{
                    return Yii::t('cron', 'Модель "'.$sec.'" модуля "'.$module->id.'" не найдена.');
                }
            }elseif(!$method){
                if(method_exists($model, $sec)){
                    $method = $model->$sec();
                    $result[] = $method;
                }else{
                    return Yii::t('cron', 'Метод "'.$sec.'" модели "'.get_class($model).'" модуля "'.$module->id.'" не найден.');
                }
            }
        }
        return $result;
    }
}
?>
