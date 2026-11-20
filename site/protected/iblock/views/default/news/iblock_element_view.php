<h1><?php echo $element->name; ?></h1>

<table>
    <tr>
        <td>Заголовок</td>
        <td><?php echo $element->name; ?></td>
    </tr>
    <tr>
        <td>Анонс</td>
        <td><?php echo $element->preview_text; ?></td>
    </tr>
    <tr>
        <td>Картинка для превью</td>
        <td><?php echo $element->preview_picture; ?></td>
    </tr>
    <tr>
        <td>Детальная информация</td>
        <td><?php echo $element->detail_text; ?></td>
    </tr>
    <tr>
        <td>Картинка для детальной информации</td>
        <td><?php echo $element->detail_picture; ?></td>
    </tr>
    <tr>
        <td>Дата создания</td>
        <td><?php echo $element->date_create; ?></td>
    </tr>
    <tr>
        <td>Создал</td>
        <td><?php echo $element->created_by; ?></td>
    </tr>
    <tr>
        <td>Дата редактирования</td>
        <td><?php echo $element->timestamp_x; ?></td>
    </tr>
    <tr>
        <td>Редактировал</td>
        <td><?php echo $element->modified_by; ?></td>
    </tr>
    <tr>
        <td>Дата начала</td>
        <td><?php echo $element->active_from; ?></td>
    </tr>
    <tr>
        <td>Дата завершения</td>
        <td><?php echo $element->active_to; ?></td>
    </tr>
    <tr>
        <td>Текст для поиска</td>
        <td><?php echo $element->searchable_content; ?></td>
    </tr>
    <tr>
        <td>Код для URL</td>
        <td><?php echo $element->code; ?></td>
    </tr>
    <tr>
        <td>Тэги</td>
        <td><?php echo $element->tags; ?></td>
    </tr>
    <tr>
        <td>Счетчик просмотров</td>
        <td><?php echo $element->show_counter; ?></td>
    </tr>
</table>
<br><br>
<h3>Дополнительные свойства</h3>
<table>
    <?php foreach($element->properties as $value): ?>
    <tr>
        <td><?php echo $value->property->name; ?></td>
        <td><?php 
            if($value->property->property_type == 'I'):?>
                <img src="<?php echo $value->value; ?>" width="200"/>
            <?php elseif($value->property->code == 'source'): ?>
                <a href="<?php echo $value->value; ?>"><?php echo $value->value; ?></a>
            <?php
            else:
                echo $value->value;
            endif;
        ?></td>
    </tr>
    <?php endforeach; ?>
</table>
    