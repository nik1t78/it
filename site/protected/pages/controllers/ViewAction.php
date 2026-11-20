<?php

class ViewAction extends CAction{

    /*
    public function run($category){

        VarDumper::dump($category);

        $page = Pages::model()->findByPath($category);

        VarDumper::dump($page);

        $this->controller->render('view', array('model' => $page));
    }*/
    public function run($id){
        $id = (int) $id;
        $model = new Pages;
        $page = $model->getPage($id);

        if ($page == null) {
            throw new CHttpException(404, Yii::t('system', 'Страница не найдена.'));
        }

        if (!empty($_GET['tpl'])) {
            $this->controller->layout = '//layouts/'.$_GET['tpl'];
        }

        // Перенаправление, если указан УРЛ
        if (!empty($page->redirect)){
            $this->controller->redirect($page->redirect);
        }

        $this->setBreadcrumbs($page);

        $this->controller->bodyPageTitle = $page->title;
        $this->controller->setPageTitle($page->title);

        $submenu = $page->visibleMenu()->getMenuList(1);
        if (empty($submenu) && $page->level > 2) {
            $parents = $page->getParents();
            $parent = array_pop($parents);
            $submenu = $parent->visibleMenu()->getMenuList(1);
        }

        $this->controller->sideMenu = $submenu;

        if ($page->template_id != '0' ) {
            $this->controller->layout = '//pages/layouts/'.$page->template_id;
        }

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

        $tmpArr[] = $page->title;
        //VarDumper::dump($tmpArr);
        //$tmpArr[''] = ''; //добавляем пустой элемент, чтобы крошки выводились если нет родителей кроме главной. и в конце добавлялась стрелочка
        $this->controller->breadcrumbs = $tmpArr;
        unset($tmpArr);
    }
}
