<?php foreach($elements as $element): ?>
<div class="span2 homeworkBlock">
    <div class="scheduleDate"><a href="/iblock/<?php echo $element->url; ?>"><?php echo $element->name; ?></a></div>
    <div class="sheduleInfo"><?php echo $element->preview_text; ?></div>
</div>
<?php endforeach; ?>