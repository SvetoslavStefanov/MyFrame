<?= $form = new Formbuilder($validation) ?>
    <p>
        <?= $form->label('relation_id', 'Form'); ?>
        <br />
        <?= $form->select('relation_id', array('value' => $forms)) ?>
    </p>
    <p>
        <?= $form->label('field', 'Field') ?>
        <br />
        <?= $form->input('field') ?> <!--For example: username -->
    </p>
    <p>
        <?= $form->label('rule', 'rule') ?>
        <br />
        <?= $form->input('rule') ?> <!-- For example: min_length -->
    </p>
    <p>
        <?= $form->label('value', 'Value') ?>
        <br />
        <?= $form->input('value') ?> <!-- For example: 3 -->
    </p>
    <?= $form->input('submitValidation', array('type' => 'submit', 'value' => 'Save')) ?>
<?=$form->close()?>