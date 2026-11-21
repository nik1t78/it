<?php
$this->pageTitle='Страница не найдена';
$this->bodyPageTitle = Yii::t('artcms', 'Ошибка ').$code;
$this->breadcrumbs=array(
	'Страница не найдена',
);
?>
    <?php echo CHtml::encode($message); ?>