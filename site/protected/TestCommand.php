<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class TestCommand extends CConsoleCommand{
    public function run($args){
        $emailer = new EmailMessenger;
        $emailer->send('admin@market48.ru', 'Письмо по расписанию', 'Письмо по расписанию');
    }
}
?>
