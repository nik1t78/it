<?php
/**
 * Класс родитель для моделей, в которых нужно реализовать разбор пути обращения к разделам/элементам.
 * Подключает поведение и задает необходимые настройки для его работы.
 */
abstract class IblockCategory extends CActiveRecord{
    private $_url;
    protected $urlPrefix = '';
    public $titleAttribute = 'title';
    public $aliasAttribute = 'alias';
    public $urlAttribute = 'url';
    public $requestPathAttribute = 'path';
    public $parentAttribute = 'parent_id';
    public $parentRelation = 'parent';
    public $defaultCriteria = array('order'=>'t.title ASC');
    public $parentFrom = 1;
    
    public function behaviors() {
        return array(
            'CategoryTreeBehavior' => array(
                'class' => 'DCategoryTreeBehavior', 
                'titleAttribute' => $this->titleAttribute,
                'aliasAttribute' => $this->aliasAttribute,
                'urlAttribute' => $this->urlAttribute,
                'requestPathAttribute' => $this->requestPathAttribute,
                'parentAttribute' => $this->parentAttribute,
                'parentRelation' => $this->parentRelation,
                'defaultCriteria' => $this->defaultCriteria,
                'parentFrom' => $this->parentFrom,
            ),
        );
    }
    
    
    public function findByPath($path, $iblock = 0)
    {
        $domens = explode('/', trim($path, '/'));
        $model = null;

        $criteria = $this->getOwnerCriteria();

        if (count($domens)==1) {

            $criteria->mergeWith(array(
                'condition'=>'t.' . $this->aliasAttribute . '=:alias AND t.' . $this->parentAttribute . '=:parentFrom',
                'params'=>array(':alias'=>$domens[0], ':parentFrom'=>$this->parentFrom)
            ));
            $model = $this->cached($this)->find($criteria);

        } else {

            $criteria->mergeWith(array(
                'condition'=>'t.' .$this->aliasAttribute . '=:alias',
                'params'=>array(':alias'=>$domens[0])
            ));
            $parent = $this->cached($this)->find($criteria);

            if ($parent){
                $domens = array_slice($domens, 1);
                foreach ($domens as $alias){
                    $model = $parent->getChildByAlias($alias, $this->getOriginalCriteria());
                    if (!$model) return null;
                    $parent = $model;
                }
            }
        }
        return $model;
    }
    
    public function rules(){
        return array(
            array('title, alias', 'required'),
            array('title, alias', 'length', 'max'=>255),
            array('parent_id', 'numerical', 'integerOnly'=>true),
        );
    }
    
    public function getUrl(){
        if($this->_url === null)
            $this->_url = Yii::app()->request->baseUrl.'/'.$this->urlPrefix . $this->cache(3600)->getPath() . Yii::app()->urlManager->urlSuffix;
        return $this->_url;
    }
}
?>
