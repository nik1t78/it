<?php

class ReceptionForm extends CFormModel
{
    public $whom;
    public $subject;
    public $fio;
    public $address;
    public $phone;
    public $email;
    public $bodyText;
    public $verifyCode;
    public $file;

    public function rules()
    {
        return array(
            array('fio, address, phone, email, bodyText', 'required'),
            array('whom, subject, fio, address', 'length', 'max'=>255),
            array('subject', 'length', 'min'=>3),
            array('phone', 'length', 'max'=>25),
            array('email', 'length', 'max'=>100),
            array('email', 'email'),
            array('phone', 'phone'),
            array('file', 'safe'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function phone($attribute, $params)
    {
        if (preg_match("/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/", $this->$attribute) == 0)
            $this->addError ($attribute, 'Неверный форма номера телефона');
    }

    public function attributeLabels()
    {
        return array(
            'whom' => 'Кому',
            'subject' => 'Тема',
            'fio' => 'ФИО',
            'address' => 'Адрес',
            'phone' => 'Контактный телефон',
            'email' => 'E-mail',
            'bodyText' => 'Вопрос',
            'verifyCode' => 'Введите код с картинки',
            'file' => 'Прикрепить файл'
        );
    }
}
