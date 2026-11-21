<?php
Yii::import('iblock.models.*');
Yii::import('pages.models.*');

class searchSimilarWidget extends CWidget
{
    public $code;

    public function run()
    {
        if (empty($this->code)) return false;

        $criteria = new CDbCriteria;
        $criteria->condition = 'code LIKE (:code) AND active="Y"';
        $criteria->params = array(
            ':code' => '%'.$this->code.'%'
        );

        $iblockRes = IBlock::model()->findAll($criteria);               //Search in iblocks
        $iblockSecRes = IBlockSection::model()->findAll($criteria);     //Search in iblock sections
        $iblockElRes = IBlockElement::model()->findAll($criteria);      //Search in iblock elements

        $criteria = new CDbCriteria;
        $criteria->condition = 'name LIKE (:code)';
        $criteria->params = array(
            ':code' => '%'.$this->code.'%'
        );
        $pageRes = Pages::model()->findAll($criteria);                  //Search in pages

        $modulesRes = array();
        //VarDumper::dump(Yii::app()->modules);
        foreach (Yii::app()->modules as $key => $mod) {                 //Search in modules
            if ($key == 'backend' || $key == 'pages' || $key == 'banner' || $key == 'filemanager' || $key == 'menu' || $key == 'settings' || $key == 'textblock' || $key == 'user' || $key == 'gii') continue;
            preg_match("/".$this->code."/", $key, $matches);
            if (!empty($matches)) {
                $modulesRes[] = array('url'=>$key, 'name'=>Yii::app()->getModule($key)->name);
            }
        }

        $this->render('result', array(
            'iblocks' => $iblockRes,
            'iblockSecs' => $iblockSecRes,
            'iblockEls' => $iblockElRes,
            'pages' => $pageRes,
            'modules' => $modulesRes
        ));
    }
}
