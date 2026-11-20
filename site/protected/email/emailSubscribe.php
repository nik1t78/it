<html>
    <head>
        <title>Рассылка новостей Департамента культуры администрации города Липецка</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="ru" http-equiv="content-language" />
        <style>
            body{
                font-family: Arial;
                font-size: 14px;
                font-weight: normal;
                background-color: #ffffff;
                color: #1c1c1c;
            }
            a{
                color: #1c1c1c;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div style="text-align: center;">
            <a style="color: #1c1c1c; text-decoration: none;" href="<?php echo Yii::app()->params['host']; ?>"><img src="<?php echo Yii::app()->params['host']; ?>/images/logo.png" /></a>
        </div>
        
        <h1>Новостная рассылка Департамента культуры администрации города Липецка</h1>
        
<?php
foreach ($list as $news):
?>
<div style="margin-bottom: 15px; padding: 5px 10px;">
    <table cellpadding="0" cellspacing="0" border="0">
        <tr valign="top">
            <td><div style="width: 260px;"><?php if (!empty($news->preview_picture)): ?><img src="<?php echo Yii::app()->params['host'].$news->preview_picture; ?>" width="200" /><?php endif; ?></div></td>
            <td>
                <div style="font-size: 11px; color: #838383; margin-top: 5px;"><?php echo DateFormat::convertDbToStr($news->active_from); ?></div>
                <div style="font-size: 13px; font-weight: bold; color: #212121;"><a style="color: #212121 !important;" href="<?php echo Yii::app()->params['host'].$news->url; ?>"><?php echo $news->name; ?></a></div>
                <div style="color: #707070; font-style: italic;"><?php echo $news->preview_text; ?></div>
            </td>
        </tr>
    </table>  
</div>
<?php endforeach; ?>
        <div style="margin-top: 40px; font-style: italic; font-size: 12px;">Вы можете отписаться от новостной рассылки, заполнив форму на <a style="text-decoration: underline;" href="<?php echo Yii::app()->params['host'].'/iblock/subscribe/index'; ?>">этой странице</a> и нажав кнопку "Отписаться".</div>
    </body>
</html>


