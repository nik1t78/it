<?php foreach($elements as $element): ?>
<div class="newsBlock span3">
    <div class="newsTitle"><a href="/iblock/<?php echo $element->url; ?>"><?php echo $element->name; ?></a></div>
    <div class="newsAnons"><?php echo $element->preview_text; ?></div>
</div>
<?php endforeach; ?>