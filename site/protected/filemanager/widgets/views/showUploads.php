<div id="filemanagerWidget">
	<h3>Загруженные файлы</h3>
	<div id="fileManagerTree">
		<table cellpadding="0" cellspacing="0" width="100%">
        	<thead>
               	<tr>
                   	<th width="20"></th>
                   	<th>Название</th>
                   	<th width="120">Размер</th>
                   	<th width="175">Изменен</th>
                   	<th width="60">Права</th>
                </tr>
                <tr>
                   	<td></td>
                   	<td colspan="4"><strong>Путь:</strong> <div id="filemanagerPath" class="inlineBlock">upload/</div></td>
                </tr>
            </thead>
            <tbody>
            <?php foreach($files['items'] as $file): ?>
                <tr>
                    <td><a href="#" class="insertLink" title="<?=Yii::t('filemanager', 'Вставить ссылку на эту страницу'); ?>"></a></td>
                    <td><a href="#" onclick="filemanagerGo('<?=$file['link'];?>'); return false;"><span class="itemIcon <?=$file['type'];?>"></span><?=$file['filename'];?></a></td>
                    <td><?=$file['filesize'];?></td>
                    <td><?=$file['time'];?></td>
                    <td><?=$file['perms'];?></td>
                </tr>
            <?php endforeach; ?>
                <tr id="statusBar">
                    <td colspan="5">Элементов: <?=$files['count'];?></td>                        
                </tr>
            </tbody>
            <tfoot>
              	<td colspan="5">
               		<div class="button-type2 noMargin" style="display: none;"><a href="#" id="filemanagerDelete" onclick="removeFolder(); return false;">Удалить выделенные</a></div>
               	</td>
            </tfoot>
        </table>
	</div>
	<a href="#" id="filemanagerWidget-close"></a>
</div>