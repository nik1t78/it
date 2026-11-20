
<?php if(!empty($sections)): ?>
<h3><?php echo $iblock->sections_name;?>:</h3>
    <ul>
    <?php foreach($sections as $sec): ?>
        <li><?php echo CHtml::link($sec->name, Yii::app()->createUrl('iblock/default/view', array('iblock'=>$iblock->code, 'section'=>$sec->path))); ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if(!empty($elements)): ?>
<h3><?php echo $iblock->elements_name;?>:</h3>
<ul>
    <?php foreach($elements as $element): ?>
    <li><?php echo CHtml::link($element->name, Yii::app()->createUrl('iblock/default/element', array('iblock'=>$iblock->code, 'section'=>$element->section->path, 'e_code'=>$element->code))); ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
