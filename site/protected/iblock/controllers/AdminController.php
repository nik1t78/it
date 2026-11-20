<?php

class AdminController extends BackendController{

    public $defaultAction = 'index';
    public $menu = array();

    public function init(){
        parent::init();

        if(!Yii::app()->user->checkAccess('viewIBlock')){
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }

        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('iblock', $val), 'url' => array('/iblock/admin/'.$key));
        }
    }

    public function actions(){
        return array(
            'index' => array(
                'class' => 'application.modules.iblock.controllers.admin.IndexAction'
            ),
            'list' => array(
                'class' => 'application.modules.iblock.controllers.admin.ListAction'
            ),
            'view' => array(
                'class' => 'application.modules.iblock.controllers.admin.ViewAction'
            ),
            'create' => array(
                'class' => 'application.modules.iblock.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.iblock.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.iblock.controllers.admin.DeleteAction'
            ),
            'publish' => array(
                'class' => 'application.modules.iblock.controllers.admin.PublishAction'
            ),
            'ajax' => array(
                'class' => 'application.modules.iblock.controllers.admin.AjaxAction'
            )
        );
    }

    function actionConverter()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'type=:type AND status=1';
        $criteria->params = array(
            ':type' => 'story'
        );
        $oldModel = OldTable::model()->findAll($criteria);
        $result = array();

        foreach ($oldModel as $news) {
            //$image = YiiBase::getPathOfAlias('webroot').'/upload/news/1_'.$news->id.'.jpg';
            //echo $image;

            $newElement = new IBlockElement;
            $newElement->timestamp_x = date('Y-m-d H:i:s', $news->changed);
            $newElement->active_from = date('Y-m-d H:i:s', $news->created);
            $newElement->iblock_id = 1;
            $newElement->iblock_section_id = 0;
            $newElement->active = "Y";
            $newElement->sort = 100;
            $newElement->name = $news->title;
            /*if (file_exists($image)) {
                $newElement->detail_picture = '/upload/news/1_'.$news->id.'.jpg';
            }*/
            //$newElement->preview_picture = '/upload/news_img/'.$news->foto;
            $newElement->preview_text = strip_tags($news->content->teaser);
            $newElement->detail_text = $news->content->body;
            $newElement->code = Misc::translit($news->title);
            $newElement->rss_enable = 'N';

            if ($newElement->save()) {
                $result['success'] = isset($result['success']) ? $result['success'] + 1 : 1;
            } else {
                $result['error'] = isset($result['error']) ? $result['error'] + 1 : 1;
            }


            //VarDumper::dump($news->attributes);
            //VarDumper::dump($newElement->attributes);
        }

        VarDumper::dump($result);
    }


    /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $criteria='')
	{
		$model=IBlock::model()->findByPk($id, $criteria);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	public function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='iblock-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
