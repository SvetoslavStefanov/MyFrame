<h2><?= $title ?></h2>
<?= link_to("FormValidations/new", "Add new form"); ?>
<br />
<?= link_to("Validations/new", "Add new Validation"); ?>
<table border="0">
    <thead><tr>
            <td>#</td>
            <td>Name</td>
            <td>Address1</td>
            <td>Address2</td>
            <td></td>
        </tr></thead>
    <tbody><? foreach ($formValidations as $validation): ?>
            <tr>
                <td><?= $validation->id ?></td>
                <td><?= link_to("Validations/show/{$validation->id}", $validation->name) ?></td>
                <td><?= $validation->address1 ?></td>
                <td><?= $validation->address2 ?></td>
                <td>
                    <?=link_to("FormValidations/edit/{$validation->id}", "edit");?>
                    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                    <?=link_to("FormValidations/edit/{$validation->id}", "del");?>
                </td>
            </tr>
        </tbody>
    <? endforeach; ?>
</table>