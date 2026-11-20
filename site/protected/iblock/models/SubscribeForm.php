<?php

class SubscribeForm extends CFormModel
{
    public $email;
    public $verifyCode;
    public $iblock_id;

    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('iblock_id', 'safe'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function attributeLabels()
    {
        return array(
            'email' => 'E-mail',
            'verifyCode' => 'Проверочный код'
        );
    }
}
