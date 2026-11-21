<?php

/**
 * This is the model class for table "{{iblock_subscribe}}".
 *
 * The followings are the available columns in table '{{iblock_subscribe}}':
 * @property integer $id
 * @property string $email
 * @property integer $iblock_id
 */
class IBlockSubscribe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IblockSubscribe the static model class
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
		return '{{iblock_subscribe}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'required'),
			array('iblock_id', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, iblock_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'iblock_id' => 'Iblock',
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


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
    public function send()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'iblock_id ASC';
        
        $subscribeUsers = IBlockSubscribe::model()->findAll($criteria);
        
        $criteria = new CDbCriteria();
        $criteria->order = 'active_date DESC';
        $subscribeModel = IBlockSubscribeList::model()->find($criteria);
        
        if ($subscribeModel == null) 
            throw new CDbException(Yii::t('iblock', 'Прошлые рассылки не найдены. Произведите рассылку вручную.'));
        
        if ($subscribeModel->success == IBlockSubscribeList::NO_SUCCESS) {
            //Если у прошлой рассылке стоит флаг "не успешно", то нужно ее повторить
            
            $criteria = new CDbCriteria();
            $criteria->condition = 'iblock_id=:iblock_id AND (id > :start_element AND id <= :end_element)';
            $criteria->params = array(
                ':iblock_id' => $subscribeModel->iblock_id,
                ':start_element' => $subscribeModel->start_element,
                ':end_element' => $subscribeModel->end_element
            );
        } else {
            //Если у прошлой рассылке стоит флаг "успешно", производим новую рассылку
            
            $criteria = new CDbCriteria();
            $criteria->condition = 'iblock_id=:iblock_id AND id > :end_element'; 
            $criteria->params = array(
                ':iblock_id' => $subscribeModel->iblock_id,
                ':end_element' => $subscribeModel->end_element
            );
        }
        
        $subscribeList = IBlockElement::model()->findAll($criteria);
        
        if (empty($subscribeList)) {
            echo 'Новостей для рассылки нет';
            return true;
        }
        
        $logResult = array();
        
        $emailer = new EmailMessenger();
        $emailer->from = Yii::app()->params->adminEmail;
        
        //Yii::app()->getController()->renderFile(Yii::getPathOfAlias('application.views.email').'/'.'emailSubscribe.php', array('list'=>$subscribeList));
        //$emailBody = Yii::app()->getController()->renderFile(Yii::getPathOfAlias('application.views.email').'/'.'emailSubscribe.php', array('list'=>$subscribeList), true);
        $emailBody = CConsoleCommand::renderFile(Yii::getPathOfAlias('application.views.email').'/'.'emailSubscribe.php', array('list'=>$subscribeList), true);
        
        foreach ($subscribeUsers as $user) {
            $logResult[] = $emailer->send($user->email, Yii::t('iblock', 'Рассылка новостей пресс-службы Администрации города Липецка.'), $emailBody); 
        }
        
        /**
         * Проверить, отправились ли письма. Если отправились - в запись подписки добавить флаг успешно (если стоял не успешный). Или создать новую запись
         */ 
        if ($subscribeModel->success == IBlockSubscribeList::SUCCESS) {
            $newTask = new IBlockSubscribeList();
            $newTask->iblock_id = $subscribeModel->iblock_id;
            $newTask->start_element = $subscribeModel->end_element;
            $newTask->end_element = $subscribeList[count($subscribeList)-1]->id;
            $newTask->active_date = date('Y-m-d H:i:s');
        } else {
            $newTask = &$subscribeModel;
        }
        
        $newTask->success = IBlockSubscribeList::SUCCESS;
        
        $newTask->save(false);
        
        return $logResult; //Возвращаем лог для записи в лог ))
    }
}