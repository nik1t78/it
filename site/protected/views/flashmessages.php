<?php if (Yii::app()->user->hasFlash($this->error)): ?>
    <div class='<?php echo $this->divId;?> error'>
        <h3 class="serviceMessageHead"><a href="#" onclick="slideFlashText(this); return false;">Ошибка!</a></h3>
        <div class='serviceMessageBody'>
            <?php echo Yii::app()->user->getFlash($this->error);?>
        </div>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->warning)): ?>
    <div class='<?php echo $this->divId;?> warning'>
        <h3 class="serviceMessageHead"><a href="#" onclick="slideFlashText(this); return false;">Внимание!</a></h3>
        <div class='serviceMessageBody'>
            <?php echo Yii::app()->user->getFlash($this->warning);?>
        </div>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->notice)): ?>
    <div class='<?php echo $this->divId;?> notice'>
        <h3 class="serviceMessageHead"><a href="#" onclick="slideFlashText(this); return false;">Сообщение</a></h3>
        <div class='serviceMessageBody'>
            <?php echo Yii::app()->user->getFlash($this->notice);?>
        </div>
    </div>
<?php endif;?>

<?php if (Yii::app()->user->hasFlash($this->success)): ?>
    <div class='<?php echo $this->divId;?> success'>
        <h3 class="serviceMessageHead"><a href="#" onclick="slideFlashText(this); return false;">Выполнено</a></h3>
        <div class='serviceMessageBody'>
            <?php echo Yii::app()->user->getFlash($this->success);?>
        </div>
    </div>
<?php endif;?>
