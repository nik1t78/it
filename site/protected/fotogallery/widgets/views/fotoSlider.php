<div class="fotoSliderWidget">
    <div class="left_arrow"><div class="arrowico_left"></div></div>
    <div class="fotoSliderCenter">
        <ul>
            <?php  foreach ($fotos as $foto): ?>
            <li><a title="<?php echo CHtml::encode($foto->alt); ?>" class="fancyImg" rel="gallerySlider" href="<?php echo $foto->image; ?>"><img width="120" height="70" alt="<?php echo CHtml::encode($foto->alt); ?>" src="<?php echo !empty($foto->preview) ? $foto->preview : $foto->image; ?>" /></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="right_arrow"><div class="arrowico_right"></div></div>
</div>