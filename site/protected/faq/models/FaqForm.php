<?php

/**
 * Created by PhpStorm.
 * User: covax
 * Date: 07.11.16
 * Time: 18:04
 */
class FaqForm extends CFormModel
{
    public $q_author;
    public $q_email;
    public $q_date;
    public $question;
    public $verifyCode;

    public function rules()
    {
        return array(
            array('q_author, q_email, q_date, question', 'required'),
            array('q_author', 'length', 'max'=>255),
            array('q_email', 'length', 'max'=>100),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function attributeLabels()
    {
        return array(
            'q_author' => 'Автор вопроса',
            'q_email' => 'Email',
            'q_date' => 'Дата вопроса',
            'question' => 'Ваш вопрос',
            'verifyCode' => 'Введите код'
        );
    }
}