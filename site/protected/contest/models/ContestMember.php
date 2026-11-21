<?php

/**
 * This is the model class for table "{{contest_member}}".
 *
 * The followings are the available columns in table '{{contest_member}}':
 * @property integer $id
 * @property integer $contest_id
 * @property string $address
 * @property string $author
 * @property string $title
 * @property integer $vote
 * @property string $photo1
 * @property string $photo2
 * @property string $photo3
 * @property string $photo4
 *
 * The followings are the available model relations:
 * @property Contest $contest
 */
class ContestMember extends CActiveRecord
{
    const ACTIVE = 'Y';
    const NOT_ACTIVE = 'N';

    public static $listTypes = array(
        '0' => '-- выберите --',
        '1' => 'Государственная',
        '2' => 'Муниципальная',
        '3' => 'Частная',
        '4' => 'Общественная',
    );

    public static $listOwned = array(
        '0' => '-- выберите --',
        '1' => 'В собственном помещении',
        '2' => 'В арендованном помещении',
    );

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContestMember the static model class
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
		return '{{contest_member}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contest_id, author, title', 'required'),
			array('contest_id, vote, nomination_id', 'numerical', 'integerOnly'=>true),
			array('address_u, address_f, author, title, phone, email', 'length', 'max'=>255),
			array('active, type, owned', 'length', 'max' => 1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, contest_id, address, author, title, vote, photo1, photo2, photo3, photo4', 'safe', 'on'=>'search'),
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
            'voters' => array(self::HAS_MANY, 'ContestVoter', 'member_id'),
            'params' => array(self::HAS_MANY, 'ContestParamValue', 'member_id'),
            'files' => array(self::HAS_MANY, 'ContestParamFile', 'member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'contest_id' => 'Конкурс',
			'nomination_id' => 'Номинация',
			'address_u' => 'Юридический адрес',
			'address_f' => 'Фактический адрес объекта',
			'author' => 'ФИО руководителя',
			'title' => 'Полное наименование организации',
			'vote' => 'Голоса',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'active' => 'Активен',
            'type' => 'Вид собственности',
            'owned' => 'Объект находится',
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
		$criteria->compare('contest_id',$this->contest_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('vote',$this->vote);
		$criteria->compare('photo1',$this->photo1,true);
		$criteria->compare('photo2',$this->photo2,true);
		$criteria->compare('photo3',$this->photo3,true);
		$criteria->compare('photo4',$this->photo4,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterSave()
    {
        $post = $_POST['ContestRequest'];

        if (!empty($post['params'])) {
            if (!empty($post['params']['files'])) {
                $files = $post['params']['files'];
                unset($post['params']['files']);

                foreach ($files as $pid => $arr) {
                    foreach ($arr as $file) {
                        if (empty($file)) continue;

                        $fileModel = new ContestParamFile();
                        $fileModel->param_id = $pid;
                        $fileModel->member_id = $this->id;
                        $fileModel->value = $file;
                        $fileModel->save(false);
                    }
                }
            }

            foreach ($post['params'] as $pid => $param) {
                if (empty($param)) continue;
                $paramModel = new ContestParamValue();
                $paramModel->param_id = $pid;
                $paramModel->member_id = $this->id;
                $paramModel->value = $param;
                $paramModel->active = 'Y';
                $paramModel->save(false);
            }
        }

    }
}