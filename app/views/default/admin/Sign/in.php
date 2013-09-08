<?= $form = new FormBuilder($sign, array('action' => 'Sign/login')) ?>
    <h4><?=$title ?></h4>
    <?= $form->label('username', 'Username') ?>
    <?= $form->input('username') ?>

    <br />

    <?= $form->label('password', 'Password') ?>
    <?= $form->input('password', array('type' => 'password')) ?>

    <br />

    <?= $form->input('submitUser', array('type' => 'submit', 'value' => 'Register', 'class' => 'blue')) ?>
<?=$form->close()?>