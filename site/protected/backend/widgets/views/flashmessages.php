<?php if (Yii::app()->user->hasFlash($this->error)): ?>    
    <div class="nNote nFailure hideit">
        <p><strong>ОШИБКА: </strong><?php echo Yii::app()->user->getFlash($this->error);?></p>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->warning)): ?>    
    <div class="nNote nWarning hideit">
        <p><strong>ВНИМАНИЕ: </strong><?php echo Yii::app()->user->getFlash($this->warning);?></p>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->notice)): ?>
    <div class="nNote nInformation hideit">
        <p><strong>ИНФОРМАЦИЯ: </strong><?php echo Yii::app()->user->getFlash($this->notice);?></p>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->success)): ?>  
    <div class="nNote nSuccess hideit">
        <p><strong>ВЫПОЛНЕНО: </strong><?php echo Yii::app()->user->getFlash($this->success);?></p>
    </div>
<?php endif;?>
