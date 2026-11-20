<?php

class ExportAction extends CAction
{
    public function run()
    {
        if (isset($_POST['Reception_filter'])) {
            $startDate = date('Y-m-d', strtotime($_POST['Reception_filter']['start']));
            $endDate = date('Y-m-d', strtotime($_POST['Reception_filter']['end']));
            
            $criteria = new CDbCriteria;
            $criteria->condition = 'date >= :start AND date <= :end';
            $criteria->params = array(
                ':start' => $startDate,
                ':end' => $endDate
            );
            
            $receptions = Reception::model()->findAll($criteria);
            
            $mPDF = Yii::app()->ePdf->mpdf();
            //$this->controller->renderPartial('export', array('receptions'=>$receptions, 'startDate'=>$_POST['Reception_filter']['start'], 'endDate'=>$_POST['Reception_filter']['end']));
            $mPDF->WriteHTML($this->controller->renderPartial('export', array('receptions'=>$receptions, 'startDate'=>$_POST['Reception_filter']['start'], 'endDate'=>$_POST['Reception_filter']['end']), true));
            $mPDF->Output();
        } else {
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('reception', 'Не указан период для экспорта обращений.'));
            $this->controller->redirect(Yii::app()->createUrl('reception/admin/index'));
        }
    }
}