<?php foreach($elements as $element): ?>
<?php if(!empty($element->preview_picture)): ?>
    <div class="birthdayImg pull-left">
        <img src="<?php echo $element->preview_picture; ?>" />
    </div>
<?php endif; ?>
<div class="birthdayInfo pull-left">
    <a href="/iblock/<?php echo $element->url; ?>"><?php echo $element->name; ?></a>
    <p><?php echo $element->preview_text; ?></p>
</div>
<?php endforeach; ?>