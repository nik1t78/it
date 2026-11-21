<?php

/**
 * This is the model class for table "{{contest_stage}}".
 *
 * The followings are the available columns in table '{{contest_stage}}':
 * @property string $date_from
 * @property string $date_to
 * @property string $title
 * @property integer $contest_id
 *
 * The followings are the available model relations:
 * @property Contest $contest
 */
class ContestStage extends CActiveRecord
{
    private $_days;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContestStage the static model class
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
		return '{{contest_stage}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_from, date_to, contest_id', 'required'),
			array('contest_id, duration', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('date_from, date_to, title, contest_id', 'safe', 'on'=>'search'),
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
			'contest' => array(self::BELONGS_TO, 'Contest', 'contest_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'date_from' => 'Дата начала',
			'date_to' => 'Дата окончания',
			'title' => 'Название',
			'contest_id' => 'Конкурс',
                        'duration' => 'Продолжительность'
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

		$criteria->compare('date_from',$this->date_from,true);
		$criteria->compare('date_to',$this->date_to,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('contest_id',$this->contest_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function getDays()
        {
            if ($this->_days == null) {
                $interval = DateFormat::compareDate($this->date_from, $this->date_to, '%a');
                $this->_days = $interval+1;
            }
            return $this->_days;
        }

    public function beforeSave()
    {
        parent::beforeSave();

        $this->date_from = date('Y-m-d H:i:s', strtotime($_POST['ContestStage']['date_from']));
        $this->date_to = date('Y-m-d H:i:s', strtotime($_POST['ContestStage']['date_to']));

        return true;
    }

    public function afterSave()
    {
        parent::afterSave();

        if (empty($this->duration) || $this->duration == 0) {
            $model = ContestStage::model()->findByPk($this->id);
            $model->duration = $this->days;
            $model->save(false);
        }
    }
}