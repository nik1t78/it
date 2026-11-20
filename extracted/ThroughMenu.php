<?php

/**
 * ThroughMenu class file.
 *
 */

/**
 * ThroughMenu наследует класс CMenu и выводит многоуровневое меню, используя HTML списки.
 *
 * ThroughMenu получает из модели Pages элементы первого и второго уровней, формирует из них
 * массив и передает его классу CMenu.
 *
 * The following example shows how to use ThroughMenu:
 * <pre>
 * $this->widget('ThroughMenu');
 * </pre>
 *
 */

Yii::import('zii.widgets.CMenu');

class ThroughMenu extends CMenu{
    
	/**
	 * Получаем из модели элементы первого и второго уровней
	 */
    protected function getItems(){
        $model=Pages::model()->order_by_id()->findAll('level=:level AND id!=1', array(':level'=>1));
        $modelSize = count($model);
        if($modelSize == 0){ //массив пуст, записи не найдены
            echo 'Ничего не нашлось';
        }else{
            $this->items[] = array('label'=>'Главная', 'url'=>'/'); //статическая ссылка на главную
            foreach($model as $item){ //проходим циклом по всем полученным из БД элементам 1-го уровня
                $pages = Pages::model()->order_by_id()->findAll('level=:level AND parent_id=:parent_id', array(':level'=>2, ':parent_id'=>$item->id)); // получаем дочерние элементы 2-го уровня для каждого эл-та 1-го уровня
                $subItems = array();
                foreach($pages as $page){ 
                    $subItems[] = array('label'=>$page->title, 'url'=>array('/'.$item->url.'/'.$page->url));
                }   
                $this->items[] = array('label'=>$item->title, 'url'=>array('/'.$item->url), 'items'=>$subItems);
            }
            $this->items[] = array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about'));
            $this->items[] = array('label'=>'Contact', 'url'=>array('/site/contact'));
            $this->items[] = array('label'=>'Пользователи', 'url'=>array('users/index'), 'visible'=>!Yii::app()->user->isGuest);
            
            $this->items[] = array('label'=>'Вход', 'url'=>array(Yii::app()->user->loginUrl), 'visible'=>Yii::app()->user->isGuest);
            $this->items[] = array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest);
        }
		
    }
    public function init(){
        $this->activeCssClass = 'curr';
        $this->getItems();
        /** вызывается функция, формирующая массив $this->items **/
        parent::init();
    }
}
