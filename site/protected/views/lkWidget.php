<h3>Здравствуйте, <?php echo Yii::app()->user->getState('full_name'); ?> <span class="loginFade"></span></h3><div class="formButton1 inlineBlock"><?php echo CHtml::link(Yii::t('artcms', 'Выход'), '/user/account/logout'); ?></div>
                	<div class="userStatus">
                            <?php //echo Users::getRole(Yii::app()->user->getState('role')); 
                                echo Contragent::getContragentByUserId(Yii::app()->user->id)->name;
                                if(Contragent::getContragentByUserId(Yii::app()->user->id)->confirm == Contragent::NON_CONFIRM)
                                    echo '<span class="blocked">Организация заблокирована</span>';
                            ?>
                        </div>
                        <?php if(Yii::app()->user->checkAccess('backendManagement')): ?>
                        <?php echo CHtml::link(Yii::t('system', "Администрирование"), array('/backend/'), array('class'=>'lkLink')) ?><br/>
                        <?php endif;?>
                	<?php echo CHtml::link(Yii::t('user', "Личный кабинет"), array('/user/account/office'), array('class'=>'lkLink')) ?><span class="sep">|</span><?php echo CHtml::link(Yii::t('user', "Активные торги [".$auctionsCount."]"), array('/user/account/mybids'), array('class'=>'lkLink')) ?><span class="sep">|</span><?php echo CHtml::link(Yii::t('user', "Корзина [".$basketCount."]"), array('/user/account/basket'), array('class'=>'lkLink', 'id' => 'basketLink')) ?>




