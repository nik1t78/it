<?php foreach($banners as $banner): ?>
<div>
    <a href="<?php echo $banner->url; ?>"><img src="<?php echo $banner->image; ?>" /></a>
</div>

<?php endforeach; ?>

