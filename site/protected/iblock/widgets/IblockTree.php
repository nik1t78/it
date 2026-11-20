<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class IblockTree extends CWidget
{
	public $iblock;
        public $htmlOptions = array();
        private $tree = array();


        public function init()
        {
            
            $cs = Yii::app()->clientScript;
            //$cs->registerCssFile(Yii::app()->theme->baseUrl."/css/iblockTree.css");
            
            $criteria = new CDbCriteria();
            $criteria->condition = 'code=:iblock_code';
            $criteria->params = array(
                ':iblock_code'=>$this->iblock
            );
            $iblock = IBlock::model()->active()->find($criteria);
            $this->tree = IBlockSection::getModelTree($iblock->id);
            //VarDumper::dump($this->tree);
        }

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
            $options = !empty($this->htmlOptions) ? $this->htmlOptions : array('class'=>'iblockTree');
            echo CHtml::openTag('ul', $options);
            $this->_viewTree($this->tree);
            echo CHtml::tag('div', array('class'=>'clear_bg'));
            echo CHtml::closeTag('ul');
            echo '<style>.content .right_new { display: none; } .text_new { width: 960px; }</style>';
            /*
            $els = IBlockElement::model()->active()->byProperty($this->propertyCode, $this->propertyValue)->findAll(array(
                'condition' => 't.iblock_id IN (SELECT id FROM {{iblock}} i WHERE i.code=:code)',
                'params' => array(
                    ':code'=>$this->iblock
                ),
                'order'=> $this->order,
                'limit'=>$this->limit,
            ));
            $iblockM = IBlock::model()->findByAttributes(array('code'=>$this->iblock));
            
            $this->render($iblockM->template . '/' . $this->template, array('elements'=>$els));*/
	}
        
        private function _viewTree($tree) 
        {
            foreach ($tree as $id => $section) {
                $liOption = array();
                if (!empty($section['items'])) {
                    $liOptions['class'] = 'main-struct';
                }
                echo CHtml::openTag('li', $liOption);
                $this->_renderItem($section['model']);
                if(!empty($section['items'])){
                    echo CHtml::openTag('ul');
                    $this->_viewTree ($section['items']);
                    echo CHtml::tag('div', array('class'=>'clear_bg'));
                    echo CHtml::closeTag('ul');
                }
                echo CHtml::closeTag('li');
            }
        }

        private function _renderItem($section) 
        {
            $firstElement = $section->firstElement;
            if (!empty($firstElement)) {
                /** Получаем свойства для каждой записи и генерируем переменный с именем, соответствующим коду свойства */
                $property = array();
                foreach ($firstElement[0]->properties as $propVal):
                    $name = $propVal->property->code;
                    $property[$name] = $propVal->value;
                endforeach;
                //$url = $firstElement[0]->url;
                if (!empty($property['link'])) {
                    $url = $property['link'];
                } else {
                    $url = '';
                }
                
            } else {
                $url = '';
            }
            
            echo '<div class="struct-btn">';
            if (!empty($url))
                echo '<a href="'.Yii::app()->createUrl($url).'">';
                
            echo $section->name;
            if (!empty($url))
                echo '</a>';
            echo '</div>';
        }
}
