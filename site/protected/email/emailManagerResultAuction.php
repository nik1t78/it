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
                border: 1px solid #000; border-collapse: collapse;
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
        
            <table class="auctionInfo" style="margin-bottom: 20px;">
                <tr>
                    <th style="text-align: left; padding: 5px 8px; font-weight: bold;">Номер аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['id']; ?></td><th style="text-align: left; padding: 5px 8px; font-weight: bold;">Шаг аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['highest_bid']; ?> руб.</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 5px 8px; font-weight: bold;">Начало аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['active_from']; ?></td><th style="text-align: left; padding: 5px 8px; font-weight: bold;">Завершение аукциона</th><td style="text-align: left; padding: 5px 8px;"><?php echo $auction['active_to']; ?></td>
                </tr>
            </table>
            <?php
            //VarDumper::dump($users);
            ?>
            <h4 style="font-family: Arial; font-size: 16px; font-weight: bold; text-transform: uppercase; color: #0096DB;">Выигранные лоты</h4>
            <?php foreach($result as $lot_id => $lot): ?>
            <table class="lotsInfo" style="border: 1px solid #000; border-collapse: collapse; " width="100%" border="1" cellpadding="2">
                <tr style="font: normal 10px Arial; text-transform: uppercase;">
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">ID</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="50%">Наименование</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Ед. изм.</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Кол-во</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="10%">Стартовая цена</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="10%">Стартовая стоимость</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="5%">Кол-во ставок</th>
                    <th style="background: #C0C0C0; color: #ffffff; border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;" width="15%">Продан по цене</th>
                </tr>
                <tr style="<?php if($lot['active'] == 'N'):
                    echo 'color: #FF0000; background: #F2F2F2;';
                endif;
                ?> font: normal 12px Arial;">
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot_id; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><?php echo $lot['name']; ?></td>
                    <?php if($lot['active'] == 'Y'): ?>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['unit']['value']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['count']['value']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;"><?php echo $lot['price']['value']; ?>&nbsp;руб.</td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;"><?php echo $lot['count']['value'] * $lot['price']['value']; ?>&nbsp;руб.</td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;"><?php echo $lot['bidsCount']; ?></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; white-space: nowrap;"><?php 
                    if($lot['lastBid']['id']!== null):
                        echo $lot['lastBid']['value']; ?>&nbsp;руб./<?php echo $lot['unit']['value'];
                    endif; ?></td>
                    <?php else: ?>
                    <td colspan="6" style="text-align: center;">лот снят с торгов</td>
                    <?php endif; ?>
                </tr>
                <?php if($lot['active'] == 'Y' && $lot['bidsCount'] > 0):?>
                <tr style="font: normal 12px Arial;">
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><h5 style="font-family: Arial; font-size: 12px; font-weight: bold; color: #0096DB; margin: 5px 0px 0px;">Участники:</h5></td>
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><h5 style="font-family: Arial; font-size: 12px; font-weight: bold; color: #0096DB; margin: 5px 0px 0px; text-align: center;">Время ставки</h5></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><h5 style="font-family: Arial; font-size: 12px; font-weight: bold; color: #0096DB; margin: 5px 0px 0px; text-align: center;">Размер ставки</h5></td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><h5 style="font-family: Arial; font-size: 12px; font-weight: bold; color: #0096DB; margin: 5px 0px 0px; text-align: center;">Стоимость лота</h5></td>
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"></td>
                </tr>
                
                <?php
                $i = 1; 
                foreach($lot['bids'] as $bid_id=>$bid):
                ?>
                <tr style="font: normal 12px Arial; <?php 
                    if($i%2 == 0)
                        echo 'background: #F2F2F2;';
                    
                    if($i == 1)
                        echo 'background: #EFFEB9;';
                ?>">
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;">
                        <?php echo $users[$bid['user_id']]['organization'];?> (ИНН: <?php echo $users[$bid['user_id']]['org_tin']; ?>) <br>
                            <?php echo $users[$bid['user_id']]['name']; ?> (Email: <?php echo $users[$bid['user_id']]['email']; ?>, тел.: <?php echo $users[$bid['user_id']]['phone']; ?>)
                    </td>
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: center;">
                        <?php echo $bid['time']; ?>
                    </td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;">
                        <?php echo $bid['value']; ?>&nbsp;руб.
                    </td>
                    <td style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px; text-align: right; white-space: nowrap;">
                        <?php echo $bid['value'] * $lot['count']['value']; ?>&nbsp;руб.
                    </td>
                    <td colspan="2" style="border: 1px solid #ffffff; border-collapse: collapse; padding: 3px 8px;"><strong><?php if($i==1) echo 'выигрышная ставка'; ?></strong></td>
                </tr>
                <?php
                $i++;
                endforeach;                
                ?>
                                
                <?php endif; ?>                
            </table>
            <hr>
            <br>
            <?php endforeach; ?>
            
            </table>
                
            
            <p><em>Спасибо за сотрудничество!<br />С уважением, <?php echo Yii::app()->name; ?></em></p>
        </div>        
    </body>
</html>
