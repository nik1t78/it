<?php foreach($this->dashboard as $key=>$val): ?>
                            <div class="bodyWidget inlineBlock">                				
                				<h3><?php echo $val['title']; ?></h3>
                				<ul class="indexPageWidget">
                                    <?php foreach($val['items'] as $val): ?>
                					<li><a href="<?php echo Yii::app()->createUrl($val['url']); ?>"><span class="icon"><img src="<?php echo $val['icon']; ?>" /></span><span><?php echo $val['name']; ?></span></a></li>
                                    <?php endforeach; ?>
                				</ul>
                			</div>
<?php endforeach; ?>