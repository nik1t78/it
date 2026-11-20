<html>
    <head>        
        <meta http-equiv="content-language" content="ru" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body{
                font: normal 14px Arial;
            }
            #logo{
                margin: 10px 10px 30px;
            }
            h4{
                font: bold 16px Arial;
                text-transform: uppercase;
                color: #0096DB;
            }
            .auctionInfo{
                margin-bottom: 20px;
            }
            .auctionInfo th, .auctionInfo td{
                text-align: left; padding: 5px 8px;
            }
            .auctionInfo th{
                font-weight: bold;
            }
            .lotsInfo{
                border: 1px solid #000; border-collapse: collapse; margin-bottom: 20px;
            }
            .lotsInfo th, .lotsInfo td{
                border: 1px solid #000; border-collapse: collapse; padding: 3px 8px;
            }
        </style>
    </head>
    <body style="font-family: Arial; font-size: 14px; font-weight: normal;">
        <div id="logo" style="margin: 10px 10px 30px 10px;"><img src="<?php echo Yii::app()->params['host']; ?>/images/logo.png" /></div>
        <div id="header">
            <h4 style="font-family: Arial; font-size: 16px; font-weight: bold; text-transform: uppercase; color: #0096DB;">Результаты онлайн-торгов</h4>
            <p><?php echo $user['name']; ?>, Вы участвовали в онлайн-торгах на сайте <?php echo Yii::app()->name;?>. Ниже приведена информаци о результатах завершения торгов.</p>
            
            <table class="auctionInfo" style="margin-bottom: 20px;">
                <tr>
                    <th style="text-align: left; padding: 5px 8px; font-weight: bold;">Номер аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['id']; ?></td><th style="text-align: left; padding: 5px 8px; font-weight: bold;">Шаг аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['highest_bid']; ?> руб.</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 5px 8px; font-weight: bold;">Начало аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['active_from']; ?></td><th style="text-align: left; padding: 5px 8px; font-weight: bold;">Завершение аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['active_to']; ?></td>
                </tr>
            </table>
            
            <h4 style="font-family: Arial; font-size: 16px; font-weight: bold; text-transform: uppercase; color: #0096DB;">Лоты</h4>
            <p><em>Выделенные лоты являются выигранными Вами</em></p>
            <table class="lotsInfo" style="border: 1px solid #000; border-collapse: collapse; margin-bottom: 20px;" width="100%" border="1" cellpadding="2">
                <tr style="font: normal 10px Arial; text-transform: uppercase;">
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">ID</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="50%">Наименование</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Ед. изм.</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Кол-во</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="10%">Стартовая цена</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="10%">Стартовая стоимость</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Кол-во ставок</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="15%">Продан по цене</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="15%">Ваша ставка</th>
                </tr>
                <?php $i = 1; foreach($result as $lot_id => $lot): ?>
                <?php //Пропускаем лоты, в которых пользователь не участвовал 
                if(!array_key_exists($lot_id, $myBids)) continue;
//VarDumper::dump($lot_id);VarDumper::dump($lot['lastBid']);VarDumper::dump($myBids);
                ?>
                <tr style="<?php 
                    if($lot['active'] == 'N'): echo 'color: #FF0000; background: #F2F2F2;'; 
                    else:
                            if($i%2 == 0) echo 'background: #F2F2F2;'; 
                            if(array_key_exists($lot['lastBid']['id'], $myBids[$lot_id])) echo 'font-weight: bold; background-color: #84B550; color: #fff;';
                    endif;  ?> font: normal 12px Arial;">
                    <?php         //VarDumper::dump($myBids); ?>
                    <!--
                <tr style="<?php /*if(isset($myBids[$lot_id])):
                    echo  $myBids[$lot_id][0]['winner'] == true ? 'font-weight: bold; background-color: #84B550; color: #fff;' : ''; 
                endif; 
                if($lot['active'] == 'N'):
                    echo 'color: #FF0000; background: #F2F2F2;';
                endif;*/
                ?>">-->
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot_id; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><?php echo $lot['name']; ?></td>
                    <?php if($lot['active'] == 'Y'): ?>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['unit']['value']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['count']['value']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;"><?php echo $lot['price']['value']; ?>&nbsp;руб.</td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;"><?php echo $lot['count']['value'] * $lot['price']['value']; ?>&nbsp;руб.</td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['bidsCount']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;  text-align: right; white-space: nowrap;"><?php 
                    if($lot['lastBid']['id']!== null):
                        echo $lot['lastBid']['value']; ?>&nbsp;руб./<?php echo $lot['unit']['value'];
                    endif; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;  text-align: right; white-space: nowrap;">
                        <?php  
                            $val = array_shift($myBids[$lot_id]);
                            echo $val['value'];
                            ?>&nbsp;руб.
                    </td>
                    <?php
                    else: 
                    ?>
                    <td colspan="7" style="text-align: center;">лот снят с торгов</td>
                    <?php
                    endif;
                    ?>
                </tr>
                <?php $i++; endforeach; ?>
                <?php
                   // echo '<tr><td>'.$product['name'].'</td><td align="center">'.$product['countOrder'].'</td><td align="right">'.$product['price'].'руб.</td><td align="right">'.$product['countOrder'] * $product['price'].' руб.</td></tr>';
                ?>
            </table>
            
            <p><strong>Если Вы выиграли один или несколько лотов, в ближайшее время наши менеджеры свяжутся с Вами для оформления заказа.</strong></p>
            <p><em>Спасибо за сотрудничество!<br />С уважением, <?php echo Yii::app()->name; ?></em></p>
        </div>        
    </body>
</html>
