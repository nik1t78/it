<?php foreach($elements as $element): ?>
<div class="teacherNote">
    <div class="itemImg pull-left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/item1.png" /></div>
    <div class="noteInfo pull-left">
        <div class="noteTitle">
            <a href="/iblock/<?php echo $element->url; ?>"><?php echo $element->name; ?></a>
        </div>
        <div class="teacher"><?php echo $element->preview_text; ?></div>
    </div>
    <div class="clearfix"></div>
</div>
<?php endforeach; ?>