<?php echo CHtml::tag('textarea', $htmlOptions, isset($htmlOptions['encode']) && !$htmlOptions['encode'] ? $text : CHtml::encode($text)); ?>
