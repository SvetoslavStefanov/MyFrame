<table border="0">
    <tr>
        <td>#</td>
        <td>Field</td>
        <td>rule</td>
        <td>value</td>
        <td>del</td>
    </tr>
    <? foreach($validations as $validation):?>
        <tr>
            <td><?=$validation->id?></td>
            <td><?=link_to("Validations/edit/{$validation->id}", $validation->field)?></td>
            <td><?=$validation->rule?></td>
            <td><?=$validation->value?></td>
            <td><?=link_to("Validations/destroy/{$validation->id}", 'del')?></td>
        </tr>
    <? endforeach; ?>
</table>