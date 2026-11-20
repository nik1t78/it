<table cellpadding="0" cellspacing="0" width="100%" class="dTable display taskWidget newUsersTable">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th width="100">Статус</th>
            <th width="60">Действия</th>
        </tr>
    </thead>
    <tbody>
<?php /*if(empty($this->result)):?>
        <tr class="gradeA">
            <td colspan="4">Пусто</td>
        </tr>
<?php endif;*/ ?>
<?php foreach($this->result as $user): ?>
        <tr class="gradeA">
            <td class="taskP"><a href="<?php echo Yii::app()->createUrl('/user/admin/view', array('id'=>$user['id'])); ?>" title="Профиль пользователя" class="tipS"><?php echo $user['name']; ?></a></td>
            <td class="orgName"><span class="lGrey f11"><?php echo $user['email']; ?></span></td>
            <td class="orgName"><span class="lGrey f11"><?php echo $user['phoneNum']; ?></span></td>
            <td><span class="lGrey f11"><?php echo $user['role']; ?></span></td>
            <td class="actBtns"><a href="<?php echo Yii::app()->createUrl('/user/admin/update', array('id'=>$user['id'])); ?>" title="редактировать" class="tipS"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/edit.png" alt="" /></a><a href="<?php echo Yii::app()->createUrl('/user/admin/delete', array('id'=>$user['id'])); ?>" title="Удалить" class="tipS userdeleteLink"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/remove.png" alt="" /></a></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>


<script>
$(document).ready(function(){
    $("a.userconfirmLink").live("click", function(){        
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
    $("a.userdeleteLink").live("click", function(){
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
