<table cellpadding="0" cellspacing="0" width="100%" class="dTable display taskWidget newUsersTable">
    <thead>
        <tr>
            <th width="60">ID</th>
            <th>Организация</th>
            <th>Регион</th>
            <th width="100">ИНН</th>
            <th width="60">Действия</th>
        </tr>
    </thead>
    <tbody>
<?php foreach($this->result as $firm): ?>
        <tr class="gradeA">
            <td><?php echo $firm['id']; ?></td>
            <td class="orgName"><a href="<?php echo Yii::app()->createUrl('/contragent/admin/view', array('id'=>$firm['id'])); ?>" title="Профиль организации" class="tipS"><?php echo $firm['name']; ?></a></td>
            <td><?php echo $firm['region']; ?></td>
            <td><?php echo $firm['tin']; ?></td>
            <td class="actBtns">
                <?php if($firm['confirm'] != Contragent::CONFIRM): ?>
                <a href="<?php echo Yii::app()->createUrl('/contragent/admin/confirm', array('id'=>$firm['id'])); ?>" title="Активировать" class="tipS confirmLink"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/updateDone.png" alt="" /></a>
                <?php else: ?>
                <a href="<?php echo Yii::app()->createUrl('/contragent/admin/unconfirm', array('id'=>$firm['id'])); ?>" title="Заблокировать" class="tipS confirmLink block"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/updateWarning.png" alt="" /></a>
                <?php endif; ?>
                <a href="<?php echo Yii::app()->createUrl('/contragent/admin/update', array('id'=>$firm['id'])); ?>" title="Редактировать" class="tipS"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/edit.png" alt="" /></a>
                <a href="<?php echo Yii::app()->createUrl('/contragent/admin/delete', array('id'=>$firm['id'])); ?>" title="Удалить" class="tipS deleteLink"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/remove.png" alt="" /></a>
            </td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>


<script>
$(document).ready(function(){
    $("a.confirmLink").live("click", function(){        
        //var deletedRow = $(this).parents('tr');
        var linkObj = $(this);
        $.ajax({
            url: $(this).attr("href"),
            type: "POST",
            dataType: 'JSON',
            data:{
                YII_CSRF_TOKEN: "<?php echo Yii::app()->request->csrfToken; ?>",
            },
            success: function(data){ 
                if(data.status == 'error'){
                    alert(data.message);
                }else{
                    var imgSrc = '', title = '';
                    if($(linkObj).hasClass('block')){
                        imgSrc = '<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/updateDone.png';
                        title = 'Активировать';
                        href = linkObj.attr('href').replace('unconfirm', 'confirm');
                    }else{
                        imgSrc = '<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/updateWarning.png';
                        title = 'Заблокировать';
                        href = linkObj.attr('href').replace('confirm', 'unconfirm');
                    }
                    $(linkObj).toggleClass('block').attr('title', title).attr('href', href).find('img').attr('src', imgSrc);
                }
                //console.log(data);
                
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(textStatus);
            }
        }); 
        return false;
    });
    $("a.deleteLink").live("click", function(){
        if(!confirm("Вы уверены, что хотите удалить данный элемент?")) return false;
        var deletedRow = $(this).parents('tr');

        $.ajax({
            url: $(this).attr("href"),
            type: "POST",
            dataType: 'JSON',
            data:{
                YII_CSRF_TOKEN: "<?php echo Yii::app()->request->csrfToken; ?>",
            },
            success: function(data){ 
                if(data.status == 'error'){
                    alert(data.message);
                }else{
                    deletedRow.remove();
                }
                console.log(data);
                
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(textStatus);
            }
        }); 
        return false;
    });
});
    
</script>
