<?php

/**
 * Created by PhpStorm.
 * User: covax
 * Date: 22.08.16
 * Time: 11:03
 */
class AjaxController extends FrontendController
{
    public $pageSize = 10;

    public function actionList($section_code) {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }

        $iblock_code = $_POST['iblock'];
        $page = !empty($_POST['page']) ? $_POST['page'] : 1;

        $iblock = IBlock::model()->active()->findByAttributes(array('code' => $iblock_code));
        if ($iblock == null) {
            throw new CHttpException(404, 'Запрошенная страница не найдена.');
        }

        if (!empty($section_code)) {
            $section = IBlockSection::model()->active()->findByAttributes(array('code' => $section_code, 'iblock_id'=>$iblock->id));
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id';
        $criteria->params = array(
            ':iblock_id' => $iblock->id,
        );

        $params = array();
        if (isset ($section) && $section != null) {
            $criteria->addCondition('iblock_section_id=:section_id');
            $criteria->params[':section_id'] = $section->id;
            $params['section_id'] = $section->id;
        }

        $criteria->order = 'sort ASC, active_from DESC';
        $elements_count = IBlockElement::model()->active()->count($criteria);
        $pages = new CPagination($elements_count);
        $pages->pageSize = $this->pageSize;
        $pages->applyLimit($criteria);
        if ($page > $pages->pageCount) {
            return true;
        }
        $pages->setCurrentPage($page);
        $elements = IBlockElement::model()->active()->findAll($criteria);

        $params['elements'] = $elements;
        $this->render('section_list', $params);
    }

    public function actionMore() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }

        $iblock_code = $_POST['iblock'];
        $section_id = !empty($_POST['section_id']) ? $_POST['section_id'] : false;
        $page = !empty($_POST['page']) ? $_POST['page'] : 2;
        $date = !empty($_POST['date']) ? $_POST['date'] : false;

        $iblock = IBlock::model()->active()->findByAttributes(array('code'=>$iblock_code));
        if ($iblock == null) {
            throw new CHttpException(404, 'Запрошенная страница не найдена.');
        }
        $criteria = new CDbCriteria;
        $criteria->condition = 'iblock_id=:iblock_id';
        $criteria->order = 'sort ASC, active_from DESC, timestamp_x DESC';
        $criteria->params = array(':iblock_id'=>$iblock->id);

        if ($date) {
            list($day, $month, $year) = explode('.', $date);
            $criteria->addCondition('active_from >= :time1 AND active_from <= :time2');
            $criteria->params[':time1'] = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year));
            $criteria->params[':time2'] = date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $day, $year));
        }

        if ($section_id) {
            $criteria->addCondition('iblock_section_id=:section_id');
            $criteria->params[':section_id'] = $section_id;
        }

        $elements_count = IBlockElement::model()->active()->count($criteria);
        $pages = new CPagination($elements_count);
        $pages->pageSize = $this->pageSize;
        if ($page > $pages->pageCount) {
            return true;
        }
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $elements = IBlockElement::model()->active()->findAll($criteria);

        $this->render('iblock_list', array(
            'elements'=>$elements
        ));
    }

    protected function processPageRequest($param='page') {
        if (Yii::app()->request->isAjaxRequest && isset($_POST[$param])) {
            $_GET[$param] = Yii::app()->request->getPost($param);
        }
    }

    public function actionDate() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }

        if (empty($_POST['date'])) {
            Yii::app()->end();
        }
        $date = $_POST['date'];
        $iblock_code = $_POST['iblock'];
        $iblock = IBlock::model()->active()->findByAttributes(array('code' => $iblock_code));
        if ($iblock == null) {
            throw new CHttpException(404, 'Запрошенная страница не найдена.');
        }
        $page = !empty($_POST['page']) ? $_POST['page'] : 2;

        if (!empty($section_code)) {
            $section = IBlockSection::model()->active()->findByAttributes(array('code' => $section_code, 'iblock_id'=>$iblock->id));
        }

        list($day, $month, $year) = explode('.', $date);

        $criteria = new CDbCriteria();
        $criteria->condition = 'active_from >= :time1 AND active_from <= :time2 AND iblock_id=:iblock_id';
        $criteria->params = array(
            ':iblock_id' => $iblock->id,
            ':time1' => date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year)),
            ':time2' => date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $day, $year)),
        );

        $params = array();
        $params['date'] = $date;
        if (isset ($section) && $section != null) {
            $criteria->addCondition('iblock_section_id=:section_id');
            $criteria->params[':section_id'] = $section->id;
            $params['section_id'] = $section->id;
        }

        $criteria->order = 'sort ASC, active_from DESC';
        $elements_count = IBlockElement::model()->active()->count($criteria);
        $pages = new CPagination($elements_count);
        $pages->pageSize = $this->pageSize;
        if ($page > $pages->pageCount) {
            return true;
        }
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $elements = IBlockElement::model()->active()->findAll($criteria);

        $params['elements'] = $elements;


        $this->render('iblock_date', $params);
    }
}
