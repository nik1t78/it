<?php

/*
 * Класс для работы с Email. Формирует и отправляет письма с помощью библиотеки Mail/Mail_Mime или стандартным mail() - в зависимости от настроек в конфиге.
 */
class EmailMessenger{
    public $from = 'no-reply@usp.admlr.lipetsk.ru';
    public $copy;

    /**
     * Send email
     * @param type $to - string or array emails
     * @param type $subject - email subject
     * @param type $message - HTML body of the email
     * @param string $text - Text body of the email
     * @param string $attach - files to attache of the email
     * @return type - message to log
     */
    public function send($to, $subject, $message, $text=false, $attach=false, $fileType = ''){
        if(isset(Yii::app()->params['email']['sender']) && Yii::app()->params['email']['sender'] == 'Mail_Mime'){
            require_once 'Mail.php';
            require_once 'Mail/mime.php';

            $crlf = "\n";

            $headers = array(
                'From'      => $this->getFrom(),
                'Subject'   => $subject
            );

            $mime = new Mail_mime(array('eol' => $crlf));

            if($text){ // Текстовая версия письма
                $mime->setTXTBody($text);
            }

            if($attach){ // Вложение
                //$attach = '/path/to/file';
                $mime->addAttachment($attach, $fileType); // Тип файла вложения
            }

            $mime->setHTMLBody($message); //HTML версия

            $mimeparams = array();
            $mimeparams['text_encoding'] = "8bit";
            $mimeparams['text_charset'] = "UTF-8";
            $mimeparams['html_charset'] = "UTF-8";
            $mimeparams['head_charset'] = "UTF-8";

            $mimeparams['debug'] = "True";

            $body = $mime->get($mimeparams);
            $headers = $mime->headers($headers);

            @$mail =& Mail::factory('mail');

            if($this->copy){
                if(is_array($to) && is_string($this->copy)){
                    array_push($to, $this->copy);
                }elseif(is_array($to) && is_array($this->copy)){
                    $to = array_merge($to, $this->copy);
                }elseif(is_string($to) && is_string($this->copy)){
                    $to = array($to, $this->copy);
                }elseif(is_string($to) && is_array($this->copy)){
                    array_push($this->copy, $to);
                    $to = $this->copy;
                }
            }

            $result = $mail->send($to, $headers, $body);
            if($result){
                if(is_array($to)){
                    $addresses = implode(', ', $to);
                    return Yii::t('emailMessenger', 'Письма на адреса '.$addresses.' успешно отправлены.');
                }

                return Yii::t('emailMessenger', 'Письмо на адрес '.$to.' успешно отправлено.');
            }else{
                return $result::getMessage();
            }
        }else{
            if(mb_detect_encoding($subject, "UTF-8") == false)
                $subject = mb_en($subject, "UTF-8", "B", "\n");

            $boundary = md5(uniqid(time()));

            $headers[] = "MIME-Version: 1.0";

            $eol = "\r\n";
            $boundary = md5(time());
            $headers[] = "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"";
            $headers[] = "Content-Transfer-Encoding: 7bit";
            $headers[] = "This is a MIME encoded message.";

            //$headers[] = "Content-type: text/html; charset=UTF-8 ";

            $headers[] = 'From: '.$this->getFrom();
            $headers[] = 'Reply-To: '.$this->getFrom();
            if(!empty($this->copy)){
                $headers[] = 'CC:'.$this->copy;
            }
            $headers[] = "X-Mailer: PHP/".  phpversion();
            $headers[] = "X-Priority: 1 (Higuest)";
            $headers[] = "X-MSMail-Priority: High";
            $headers[] = "Importance: High";

            $headers = implode("\r\n", $headers);

            $messageText = "--$boundary\r\n";
            $messageText .= "Content-type: text/html; charset=UTF-8\r\n";
            $messageText .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $messageText .= chunk_split(base64_encode($message));

            $message=$messageText;

            if (!empty($attach)){
                $content = file_get_contents($attach);
                $content = chunk_split(base64_encode($content));

                $message .= "--" . $boundary . $eol;
                $message .= "Content-Type: application/octet-stream; name=\"" . basename($attach) . "\"" . $eol;
                $message .= "Content-Transfer-Encoding: base64" . $eol;
                $message .= "Content-Disposition: attachment" . $eol. $eol;
                $message .= $content . $eol;
            }


            if(!mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers)){
                return Yii::t('emailMessenger', 'При отправке письма возникла ошибка.');
            }else{
                return Yii::t('emailMessenger', 'Письмо на адрес '.$to.' успешно отправлено.');
            }
        }
    }

    protected function getFrom(){
        return $this->from;
    }
}
?>
