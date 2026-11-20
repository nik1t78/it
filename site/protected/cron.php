<?php

/*
 * Скрипт эмулирующий cron. Запускается кроном раз в минуту, считывает задачи и запускает те, которые в данный момент запланированы
 */

$yiic = dirname(__FILE__).'/../../../framework/yiic.php';
$config = dirname(__FILE__).'/config/cron.php';

require_once($yiic);
?>
