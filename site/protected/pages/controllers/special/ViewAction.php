<?php

class ViewAction extends CAction{
    
    public function run($id){ 
        $id = (int) $id;
        $model = new Pages;
        $page = $model->getPage($id);
        
        $this->setBreadcrumbs($page);
        
        $this->controller->bodyPageTitle = $page->title;
        /**
        $criteria = new CDbCriteria;
        //$criteria->select = 'id, name, full_name, email, role';
        $model=Pages::model()->findByPk($id, $criteria);
		if($model===null)
			throw new CHttpException(404, Yii::t('pages', 'Страница не найдена'));
        **/
        $this->controller->render('view', array('model'=> $page));
    }
    
    public function setBreadcrumbs($page){
        $path = $page->ancestors()->findAll();
        $tmpArr = array();
        $url = '/';
        
        foreach($path as $parent){
            if($parent->id == 1) continue;
            $url .= $parent->name.'/';
            $tmpArr[$parent->title] = array($url);
        }
        //VarDumper::dump($tmpArr);
        $tmpArr[''] = ''; //добавляем пустой элемент, чтобы крошки выводились если нет родителей кроме главной. и в конце добавлялась стрелочка
        $this->controller->breadcrumbs = $tmpArr;
        unset($tmpArr);
    }
}