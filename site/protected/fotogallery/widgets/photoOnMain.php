<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class photoOnMain extends CWidget
{
	public $gallery;
        public $limit = 3;
        public $onMain = false;
        public $htmlOptions=array();
    public $template = 'photoOnMain';


	/**
	 * Ends running the widget.
	 */
	public function run()
	{
            $criteria = new CDbCriteria;
            if (!empty($this->gallery)) {
                if (is_int($this->gallery)) {
                    $criteria->condition = 't.gallery_id=:gallery';
                } else {
                    $criteria->condition = 't.gallery_id=(SELECT id FROM {{foto_gallery}} g WHERE g.code=:gallery)';
                }
                $criteria->params = array(
                    ':gallery'=>$this->gallery
                );
            }
            if ($this->onMain) {
                $criteria->addCondition('t.onMain=:onMain');
                $criteria->params[':onMain'] = FotoGallery::ACTIVE;
            }
            $criteria->order = 'created DESC, sort ASC';
            $criteria->offset = 0;
            $criteria->limit = $this->limit;

            $photos = Foto::model()->active()->findAll($criteria);

            $this->render($this->template, array('photos'=>$photos));
	}

}
