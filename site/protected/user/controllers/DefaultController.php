<?php

class DefaultController extends FrontendController
{
    public $layout = '//layouts/column2';
	public function actionIndex()
	{
		$this->render('index');
	}
        
    
    /**
     * * Returns the data model based on the primary key given in the GET variable.
     * * If the data model is not found, an HTTP exception will be raised.
     * * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $criteria='')
    {
	$model=User::model()->findByPk($id, $criteria);
	if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
	return $model;
    }
}
