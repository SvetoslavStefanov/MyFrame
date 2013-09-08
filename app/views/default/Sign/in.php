<?=$form = new FormBuilder($sign, array('action' => 'Sign/login'))?>
    <p>
        <?=$form->label('username', 'Username:')?>
        <?=$form->input('username')?>
        <br /><br />
        <?=$form->label('password', 'Password')?>
        <?=$form->input('password', array('type' => 'password')) ?>
    </p>

    <?=$form->input('submitUser', array('type' => 'submit', 'value' => 'Login'))?>
<?=$form->close()?>

<br />
<?=link_to("Sign/up", "Create new user")?>