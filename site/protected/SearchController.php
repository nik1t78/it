<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');


class SearchController extends FrontendController
{
    public $layout = '//layouts/main';
    public $bodyPageTitle = 'Поиск по сайту';

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSearch()
    {
        $string = $inParam = '';
        if(empty($_POST['SiteSearchForm']) && empty($_GET['searchString']))
            $this->redirect (Yii::app ()->createUrl('search/'));

        if(isset($_POST['SiteSearchForm'])) {
            $string = $_POST['SiteSearchForm']['string'];
            $_GET['searchString'] = $string;
        } else {
            $string = $_GET['searchString'];
        }
        $term = urldecode($string);

        $ids = array();
        $result = array();

        /*
         * Поиск в страницах
         */
        $criteria = new CDbCriteria;
        $criteria->condition = 'title LIKE :term OR body LIKE :term OR anons LIKE :term';
        $criteria->params = array(
            ':term' => "%$term%"
        );
        $model = Pages::model()->findAll($criteria);
        foreach ($model as $page) {
            $label = $page->title;
            $result[] = array('id' => 'page-'.$page->id, 'label' => $label, 'value' => $label, 'href' => $page->purl);
        }


        $iblocks = array(
            'news',
            'services',
            'stories',
            'video',
            'doctors',
            'disease'
        );
        $iCriteria = new CDbCriteria;
        $iCriteria->select = 'id';
        $iCriteria->addInCondition('code', $iblocks);
        $iModels = IBlock::model()->findAll($iCriteria);
        foreach ($iModels as $iblock) {
            $ids[] = $iblock->id;
        }

        if (!empty($ids)) {
            /*
             * Поиск в элементах инфоблоков
             */
            $criteria = new CDbCriteria;
            $criteria->condition = 'name LIKE :term OR preview_text LIKE :term OR detail_text LIKE :term';
            $criteria->params = array(
                ':term' => "%$term%"
            );
            $criteria->addInCondition('iblock_id', $ids);

            $model = IBlockElement::model()->active()->findAll($criteria);
            foreach ($model as $value) {
                $label = $value->iblock->name . ': '. $value->name;
                $result[] = array('id' => 'el-'.$value->id, 'label' => $label, 'value' => $label, 'href' => $value->url);
            }

            /*
             * Поиск в разделах инфоблоков
             */
            $sCriteria = new CDbCriteria;
            $sCriteria->condition = 'name LIKE :term OR description LIKE :term';
            $sCriteria->params = array(
                ':term' => "%$term%"
            );
            $sCriteria->addInCondition('iblock_id', $ids);
            $modelS = IBlockSection::model()->active()->findAll($sCriteria);
            foreach ($modelS as $sec) {
                $label = $sec->name . ' - '.$sec->iblock->name;
                $result[] = array('id' => 'sec-'.$sec->id, 'label' => $label, 'value' => $label, 'href' => $sec->url);
            }
        }

        $this->render('found', array(
            'materials' => $result,
            'string' => $term
        ));
    }

    public function actionAutocomplete()
    {
        $term = addcslashes(Yii::app()->getRequest()->getParam('term'), '%_'); //экранировать LIKE специальные символы
        if (Yii::app()->request->isAjaxRequest && $term) {
            $ids = array();
            $result = array();

            /*
             * Поиск в страницах
             */
            $criteria = new CDbCriteria;
            $criteria->condition = 'title LIKE :term OR body LIKE :term OR anons LIKE :term';
            $criteria->params = array(
                ':term' => "%$term%"
            );
            $model = Pages::model()->findAll($criteria);
            foreach ($model as $page) {
                $label = $page->title;
                $result[] = array('id' => 'page-'.$page->id, 'label' => $label, 'value' => $label, 'href' => $page->purl);
            }


            $iblocks = array(
                'news',
                'services',
                'stories',
                'video',
                'doctors',
                'disease'
            );
            $iCriteria = new CDbCriteria;
            $iCriteria->select = 'id';
            $iCriteria->addInCondition('code', $iblocks);
            $iModels = IBlock::model()->findAll($iCriteria);
            foreach ($iModels as $iblock) {
                $ids[] = $iblock->id;
            }

            if (!empty($ids)) {
                /*
                 * Поиск в элементах инфоблоков
                 */
                $criteria = new CDbCriteria;
                $criteria->condition = 'name LIKE :term OR preview_text LIKE :term OR detail_text LIKE :term';
                $criteria->params = array(
                    ':term' => "%$term%"
                );
                $criteria->addInCondition('iblock_id', $ids);

                $model = IBlockElement::model()->active()->findAll($criteria);
                foreach ($model as $value) {
                    $label = $value->iblock->name . ': '. $value->name;
                    $result[] = array('id' => 'el-'.$value->id, 'label' => $label, 'value' => $label, 'href' => $value->url);
                }

                /*
                 * Поиск в разделах инфоблоков
                 */
                $sCriteria = new CDbCriteria;
                $sCriteria->condition = 'name LIKE :term OR description LIKE :term';
                $sCriteria->params = array(
                    ':term' => "%$term%"
                );
                $sCriteria->addInCondition('iblock_id', $ids);
                $modelS = IBlockSection::model()->active()->findAll($sCriteria);
                foreach ($modelS as $sec) {
                    $label = $sec->name . ' - '.$sec->iblock->name;
                    $result[] = array('id' => 'sec-'.$sec->id, 'label' => $label, 'value' => $label, 'href' => $sec->url);
                }
            }

            echo CJSON::encode($result);
            Yii::app()->end();
        }
    }
}
?>
