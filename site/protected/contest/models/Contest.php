<?php

/**
 * This is the model class for table "{{contest}}".
 *
 * The followings are the available columns in table '{{contest}}':
 * @property integer $id
 * @property string $title
 * @property string $about
 * @property string $image
 *
 * The followings are the available model relations:
 * @property ContestMember[] $contestMembers
 * @property ContestPage[] $contestPages
 * @property ContestStage[] $contestStages
 */
class Contest extends CActiveRecord
{
    private $_totalDays;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contest the static model class
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
		return '{{contest}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title, image', 'length', 'max'=>255),
			array('about', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, about, image', 'safe', 'on'=>'search'),
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
            'members_count' => array(self::STAT, 'ContestMember', 'contest_id'),
			'members' => array(self::HAS_MANY, 'ContestMember', 'contest_id'),
			'pages' => array(self::HAS_MANY, 'ContestPage', 'contest_id'),
			'stages' => array(self::HAS_MANY, 'ContestStage', 'contest_id', 'order' => 'stages.date_from ASC'),
            'params' => array(self::HAS_MANY, 'ContestParam', 'contest_id'),
            'nominations' => array(self::HAS_MANY, 'ContestNomination', 'contest_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название конкурса',
			'about' => 'О конкурсе',
			'image' => 'Логотип/изображение',
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
		$criteria->compare('about',$this->about,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function getTotalDays()
        {
            if ($this->_totalDays == null) {
                foreach ($this->stages as $stage) {
                    $this->_totalDays += $stage->days;
                }
            }

            return $this->_totalDays;
        }


        public static function getList(){
            $model = Contest::model()->findAll();
            $list = array();
            foreach($model as $contest){
                $list[$contest->id] = $contest->title;
            }

            return $list;
        }

        public function getTotal_vote()
        {
            $members = $this->members;
            $total = 0;

            foreach ($members as $member) {
                $votes = $member->vote;
                $total += $votes;
            }
            return $total;
        }
}