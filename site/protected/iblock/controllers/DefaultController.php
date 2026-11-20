<?php

class DefaultController extends FrontendController
{
    public $template = 'default';
    //public $layout = '//layouts/column2';
    public $bodyPageTitle = '';

    public $pageSize = 10;
    public $rssCount = 20;

    public function actionIndex()
    {             // если запросили модуль инфоблок без указания конкретного инфоблока - возвращаем ошибку
        throw new CHttpException(404, Yii::t('system', 'Страница не найдена.'));

        $this->render('index');
    }

    public function actionElement($e_code='', $section='', $iblock='')
    {
        if(empty($e_code) && empty($e_id))
            throw new CHttpException(404, 'Страница не найдена.');

        if(empty($iblock))
            throw new CHttpException(404, 'Страница не найдена.');

        $iblock = IBlock::model()->findByAttributes(array('code'=>$iblock));
        if($iblock === null)
            throw new CHttpException(404, 'Страница не найдена');

        $criteria = new CDbCriteria();
        if($e_code){
            $criteria->addCondition('code=:code');
            $criteria->params = array_merge($criteria->params, array(
                        ':code'=>$e_code,
            ));


            if($section){
                $section = IBlockSection::model()->iblock_id($iblock->id)->findByPath($section);
                if($section !== null){
                    $criteria->addCondition('iblock_section_id=:section_id');
                    $criteria->params = array_merge($criteria->params, array(
                        ':section_id'=>$section->id
                    ));
                }else{
                    throw new CHttpException(404, 'По указанному адресу страница не найдена.');
                }
            }else{
                $criteria->addCondition('iblock_section_id=0');
            }
            $element = IBlockElement::model()->active()->find($criteria);
        }

        if($element === null)
            throw new CHttpException(404, 'Запрошенная страница не найдена.');

        // Редирект, если задан
        if(!empty($element->redirect) && $element->redirect != 'NULL') {
            $this->redirect($element->redirect);
        }

        $this->pageTitle = $element->name;
        $this->breadcrumbs[$element->iblock->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$element->iblock->code));
        if($section){
            $this->breadcrumbs[$section->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$element->iblock->code, 'section'=>$section->path));
        }
        $this->breadcrumbs[] = $element->name;
        $template = $element->iblock->template == null ? $this->template : $element->iblock->template;
        $this->render($template.'/iblock_element_view', array('element'=>$element));
    }

    public function actionPrint($e_code='', $section='', $iblock='') {
        $this->actionElement($e_code, $section, $iblock);
    }


/*
    public function actionAjax()
    {
        if (Yii::app()->request->isAjaxRequest){
            //VarDumper::dump(($_POST));
            $elements = array();
            $iblock = $_POST['iblock'];
            $limit = isset($_POST['limit']) ? $_POST['limit'] : false;

            $criteriaIB = new CDbCriteria;
            $criteriaIB->condition = 'code=:code';
            $criteriaIB->params = array(
                ':code'=>$iblock
            );

            $iblockModel = IBlock::model()->find($criteriaIB);
            //Получаем шаблон инфоблока
            $template = $iblockModel->template == null ? $this->template : $iblockModel->template;

            $criteriaEl = new CDbCriteria;
            $criteriaEl->condition = 'iblock_id=:iblock_id';
            $criteriaEl->params = array(
                ':iblock_id'=>$iblockModel->id
            );
            $criteriaEl->order = 'active_from ASC';

            if ($limit) {
                $criteriaEl->limit = $limit;
            }
            if (isset($_POST['AfishaFilter'])) {
                $elementsId = array();
                foreach ($_POST['AfishaFilter'] as $key=>$value) {
                    $criteriaParam = new CDbCriteria;
                    if (is_array($value)) { //Параметр содержит мин и макс значение
                        if (!empty($value['from'])) {
                            if ($key == 'active_from' || $key == 'active_to'){
                                $value['from'] = DateFormat::convertStrToDb($value['from'], 'd.m');
                            }
                            $criteriaEl->addCondition($key.'>=:from');
                            $criteriaEl->params[':from'] = $value['from'];
                        } else {
                            $criteriaEl->addCondition($key.'>=:today');
                            $criteriaEl->params[':today'] = date('Y-m-d H:i:s');
                        }
                        if (!empty($value['to'])) {
                            if ($key == 'active_from' || $key == 'active_to'){
                                $value['to'] = DateFormat::convertStrToDb($value['to'], 'd.m');
                            }
                            $criteriaEl->addCondition($key.'<=:to');
                            $criteriaEl->params[':to'] = $value['to'];
                        }
                        continue;
                    } else { //параметр содержит единственное значение
                        if ($key == 'period') {
                            switch ($value){
                                case 'tomorrow':
                                    $active_from_mk = mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
                                    $active_to_mk = mktime(23, 59, 59, date('m'), date('d')+1, date('Y'));
                                    break;
                                case 'week':
                                    $active_from_mk = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                                    $active_to_mk = mktime(23, 59, 59, date('m'), date('d')+7, date('Y'));
                                    break;
                                case 'today':
                                default:
                                    $active_from_mk = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                                    $active_to_mk = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                                    break;
                            }
                            $criteriaEl->addCondition('active_from>=:active_from AND active_from<=:active_to');
                            $criteriaEl->params[':active_from'] = date('Y-m-d H:i:s', $active_from_mk);
                            $criteriaEl->params[':active_to'] = date('Y-m-d H:i:s', $active_to_mk);
                        } else {
                            if ((int)$value === 0) continue;
                            $criteriaParam->condition = 'iblock_property_id=(SELECT id FROM {{iblock_property}} WHERE code=:code) AND value=(SELECT value FROM {{iblock_element_property}} WHERE id=:val)';
                            $criteriaParam->params = array(
                                ':val'=>$value,
                                ':code'=>$key
                            );
                        }
                    }
                    //VarDumper::dump($criteriaParam);
                    //echo $key.'<br>';
                    if (!empty($criteriaParam->condition)) {
                        $paramModel = IBlockElementProperty::model()->findAll($criteriaParam);
                        foreach ($paramModel as $param) {
                            if (!array_search($param->iblock_element_id, $elementsId)) {
                                $elementsId[] = $param->iblock_element_id;
                            }
                        }
                        //VarDumper::dump($elementsId);
                    }
                }
                if (!empty($elementsId)) {
                    $criteriaEl->addInCondition('id', $elementsId);
                }
            } else {
                $criteriaEl->condition = 'active_from>=:curr_date';
                $criteriaEl->params[':curr_date'] = date('Y-m-d 00:00:00');
            }

            //VarDumper::dump($criteriaEl);
            $elements = IBlockElement::model()->active()->findAll($criteriaEl);

            echo $this->renderPartial($template.'/iblock_ajax_list', array('elements' => $elements));
        } else {
            throw new CHttpException(500, Yii::t('system', 'Неверный запрос.'));
        }
    } */

    public function actionView($iblock, $section='', $e_code='', $e_id='')
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'code=:code';
        $criteria->params = array(
            ':code'=>$iblock
        );
        $iblockModel = IBlock::model()->active()->find($criteria); // Получаем запрошенный инфоблок

        if($iblockModel == null){
            throw new CHttpException(404, 'Запрошеная страница не найдена.');
        }

        //Получаем шаблон инфоблока
        $template = $iblockModel->template == null ? $this->template : $iblockModel->template;
        //Устанавливаем кол-во элементов на странице
        $this->pageSize = $iblockModel->page_count;

        // Ищем запрошенный раздел в инфоблоке
        if($section){
            $sectionModel = IBlockSection::model()->active()->iblock_id($iblockModel->id)->findByPath($section);

            // Перенаправление, если указан УРЛ
            if (!empty($sectionModel->redirect) && $sectionModel != 'NULL'){
                $this->redirect($sectionModel->redirect);
            }

            if($sectionModel != null && empty($e_code) && empty($e_id)){
                $sections = IBlockSection::model()->active()->child($sectionModel->id)->findAll();
                $criteria = new CDbCriteria();
                $criteria->order = 'sort ASC, active_from DESC, timestamp_x DESC';

                $elements_count = IBlockElement::model()->active()->child($sectionModel->id)->count($criteria);
                $pages = new CPagination($elements_count);
                if ($this->pageSize > 0) {
                    $pages->pageSize = $this->pageSize;
                    $pages->applyLimit($criteria);
                }

                $elements = IBlockElement::model()->active()->child($sectionModel->id)->findAll($criteria);

                $this->pageTitle = $sectionModel->name;
                $this->breadcrumbs[$iblockModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code));
                $this->breadcrumbs[$sectionModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code, 'section'=>$sectionModel->path));

                /* РЕНДЕР СПИСКА РАЗДЕЛА */
                $this->render($template.'/iblock_section_list', array(
                    'iblock' => $iblockModel,
                    'section' => $sectionModel,
                    'sections' => $sections,
                    'elements' => $elements,
                    'pages' => $pages
                ));
                return true; //Тормозим дальнейшее выполнение
            }
        }


        if(empty($section)){

            $sections = IBlockSection::model()->active()->child(0)->findAll(array(
                'condition'=> 'iblock_id=:iblock_id',
                'params' => array(':iblock_id'=>$iblockModel->id),
				'order' => 'sort ASC',
            ));

            $criteria = new CDbCriteria();
            $criteria->order = 'active_from DESC, timestamp_x DESC';
            $criteria->condition = 'iblock_id=:iblock_id';
            $criteria->order = 'sort ASC, active_from DESC, timestamp_x DESC';
            $criteria->params = array(':iblock_id'=>$iblockModel->id);

            $elements_count = IBlockElement::model()->active()->child(0)->count($criteria);
            $pages = new CPagination($elements_count);
            if ($this->pageSize > 0) {
                $pages->pageSize = $this->pageSize;
                $pages->applyLimit($criteria);
            }

            if ($iblockModel->code == 'afisha') {
                $criteria->order = 'active_from ASC';
                $criteria->addCondition('active_from>=:active_from AND active_from<=:active_to');
                $criteria->params[':active_from'] =  date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
                $criteria->params[':active_to'] =  date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d')+7, date('Y')));
            }

            if ($iblockModel->show_all_elements == IBlockElement::NON_ACTIVE) {
                $criteria->addCondition('t.iblock_section_id=0');
            }

            $elements = IBlockElement::model()->active()->findAll($criteria);

                $this->pageTitle = $iblockModel->name;
                $this->breadcrumbs[$iblockModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code));

            $this->render($template.'/iblock_list', array(
                'iblock'=>$iblockModel,
                'sections'=>$sections,
                'elements'=>$elements,
                'pages' => $pages));                       /* РЕНДЕР СПИСКА ИНФОБЛОКА */
            return true;
        }

        throw new CHttpException(404, 'Запрошеная страница не найдена.'); //Если ничего не нашлось
    }

    public function actionDate($iblock, $date)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'code=:code';
        $criteria->params = array(
            ':code'=>$iblock
        );
        $iblockModel = IBlock::model()->active()->find($criteria); // Получаем запрошенный инфоблок

        if($iblockModel == null){
            throw new CHttpException(404, 'Запрошеная страница не найдена.');
        }

        //Получаем шаблон инфоблока
        $template = $iblockModel->template == null ? $this->template : $iblockModel->template;

        list($day, $month, $year) = explode('.', $date);
        $criteria = new CDbCriteria();
        $criteria->condition = 'active_from >= :time1 AND active_from <= :time2 AND iblock_id=:iblock_id';
        $criteria->params = array(
            ':iblock_id' => $iblockModel->id,
            ':time1' => date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year)),
            ':time2' => date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $day, $year))
        );
        $criteria->order = 'active_from DESC';

        $elements_count = IBlockElement::model()->active()->count($criteria);
        $pages = new CPagination($elements_count);
        $pages->pageSize = $this->pageSize;
        $pages->applyLimit($criteria);

        $elements = IBlockElement::model()->active()->findAll($criteria);

        $this->pageTitle = $iblockModel->name;
        $this->breadcrumbs[$iblockModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code));

        $this->render($template.'/viewDate', array('pages'=>$pages, 'iblock'=>$iblockModel, 'elements'=>$elements, 'date'=>  DateFormat::convertDbToStr($date, '', 'd.m.Y', 'long', '')));
    }

    public function actionRss($iblock)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'code=:code';
        $criteria->params = array(
            ':code' => $iblock
        );
        $iblockM = IBlock::model()->active()->find($criteria);

        if($iblockM == null){
            throw new CHttpException(404, 'Запрошеная страница не найдена.');
        }

        $yesterday = date('Y-m-d H:i:d', mktime(date('H'), date('i') -1, 0, date('m'), date('d') -1, date('Y')));

        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id AND (active_from > :currDateTime OR timestamp_x > :currDateTime)';
        $criteria->params = array(
            ':iblock_id' => $iblockM->id,
            ':currDateTime' => $yesterday
        );
        //$criteria->limit = $this->rssCount;
        $criteria->order = 'active_from DESC, timestamp_x DESC';

        $elements = IBlockElement::model()->active()->rss_enable()->findAll($criteria);

        $this->pageTitle = $iblockM->name;
        $this->layout = '//layouts/rss';

        //Получаем шаблон инфоблока
        $template = $iblockM->template == null ? $this->template : $iblockM->template;

        $this->render($template.'/feed', array(
            'iblock' => $iblockM,
            'elements' => $elements
        ));
    }

    public function actionMonthels($iblock, $year, $month)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'code=:code';
        $criteria->params = array(
            ':code' => $iblock
        );
        $iblockModel = IBlock::model()->active()->find($criteria);

        if($iblockModel == null){
            throw new CHttpException(404, 'Запрошеная страница не найдена.');
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'active_from > :time1 AND active_from < :time2 AND iblock_id=:iblock_id';
        $criteria->params = array(
            ':iblock_id' => $iblockModel->id,
            ':time1' => date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)),
            ':time2' => date('Y-m-d H:i:s', mktime(23, 59, 59, $month+1, 0, $year))
        );

        if (Yii::app()->request->isAjaxRequest) {
            $elements = IBlockElement::model()->active()->findAll($criteria);
            $result = array();

            foreach ($elements as $element) {
                $result[] = date('Y-m-d', strtotime($element->active_from));
            }

            $result = json_encode($result);
            echo $result;
        } else {
            $template = $iblockModel->template == null ? $this->template : $iblockModel->template;
            $elements_count = IBlockElement::model()->active()->count($criteria);
            $pages = new CPagination($elements_count);
            $pages->pageSize = $this->pageSize;
            $pages->applyLimit($criteria);

            $elements = IBlockElement::model()->active()->findAll($criteria);


            $this->pageTitle = $iblockModel->name;
            $this->breadcrumbs[$iblockModel->name] = Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblockModel->code));


            $this->render($template.'/viewDate', array('pages'=>$pages, 'iblock'=>$iblockModel, 'elements'=>$elements, 'date'=>  DateFormat::convertDbToStr($date, '', 'd.m.Y', 'long', '')));
        }
    }

}
