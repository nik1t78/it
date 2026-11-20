<?php 
DUrlRulesHelper::import('iblock'); ?>

<div class="portlet-materials">
    <h3>Архив новостей</h3>
    <ul>
        <?php foreach ($els as $month => $count): ?>
        <li>
            <?php echo CHtml::link("$month ($count)", Yii::app()->createUrl('iblock/default/month', array(
                'month'=>date('m.Y', strtotime($month)),
                'iblock' => $iblock_code
                ))); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
