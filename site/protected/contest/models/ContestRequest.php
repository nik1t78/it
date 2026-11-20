<?php

class ContestRequest extends CFormModel
{
    public $author;
    public $address_u;
    public $address_f;
    public $phone;
    public $email;
    public $title;
    public $file;
    public $comment;
    public $contest;
    public $nomination_id;
    public $type;
    public $owned;
    public $verifyCode;

    public function rules()
    {
        return array(
            array('author, address_u, title', 'required'),
            array('comment, contest, phone, email, address_u, address_f', 'length', 'max'=>255),
            array('type, owned', 'length', 'max' => 1),
            //array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function attributeLabels()
    {
        return array(
            'author' => 'ФИО руководителя',
            'address_u' => 'Юридический адрес',
            'address_f' => 'Фактический адрес объекта',
            'type' => 'Вид собственности',
            'owned' => 'Объект находится',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'title' => 'Наименование предприятия',
            'file' => 'Архив с фотографиями',
            'comment' => 'Комментарий к заявке',
            'nomination_id' => 'Наименование сферы деятельности участника',
            'verifyCode' => 'Код подтверждения',
        );
    }
}