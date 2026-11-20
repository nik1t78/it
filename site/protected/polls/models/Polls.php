<?php

/**
 * This is the model class for table "{{polls}}".
 *
 * The followings are the available columns in table '{{polls}}':
 * @property integer $id
 * @property string $title
 * @property string $active_from
 * @property string $active_to
 * @property string $active
 * @property string $question
 */
class Polls extends CActiveRecord
{
    const ACTIVE = 'Y';
    const NOT_ACTIVE = 'N';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Polls the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{polls}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, active_from, question', 'required'),
			array('title', 'length', 'max'=>255),
			array('active', 'length', 'max'=>1),
			array('active_to', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, active_from, active_to, active, question', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'answer_count' => array(self::STAT, 'PollsAnswers', 'poll_id'),
                    'answers' => array(self::HAS_MANY, 'PollsAnswers', 'poll_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'active_from' => 'Начало опроса',
			'active_to' => 'Завершение опроса',
			'active' => 'Активен',
			'question' => 'Вопрос',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('active_from',$this->active_from,true);
		$criteria->compare('active_to',$this->active_to,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('question',$this->question,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * Выбирает только активные элементы из БД
         * @return \IBlockElement
         */
        public function active(){
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.active=:active',
                'params'=>array(':active'=>Polls::ACTIVE),
            ));
            return $this;
        }
        
        public function afterSave() 
        {
            parent::afterSave();
            if (isset($_POST['PollsAnswers'])) {
                if (isset($_POST['PollsAnswers']['new'])) {
                    foreach ($_POST['PollsAnswers']['new'] as $answer) {
                        if (empty($answer)) continue;

                        $pollAnsw = new PollsAnswers();

                        $pollAnsw->poll_id = $this->id;
                        $pollAnsw->title = $answer;
                        $pollAnsw->save();
                    }
                }
                
                if (isset($_POST['PollsAnswers']['ans'])) {
                    foreach ($_POST['PollsAnswers']['ans'] as $id => $answer) {
                        $pollAnsw = PollsAnswers::model()->findByPk($id);
                        // Если значение ответа очистили - значит его нужно удалить
                        if (empty($answer)) {
                            $pollAnsw->delete();
                            continue;
                        }
                        $pollAnsw->title = $answer;
                        $pollAnsw->save();
                    }
                }
            }
        }
        
        public function beforeDelete()
        {
            parent::beforeDelete();
            $answers = PollsAnswers::model()->findAllByAttributes(array('poll_id' => $this->id));
            
            foreach ($answers as $answer) {
                if (!$answer->delete()) return false;
            }
            return true;
        }
}