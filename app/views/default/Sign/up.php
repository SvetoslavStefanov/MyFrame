<?=$form = new Formbuilder($sign);?>
    <?=$form->label("username", "Username"); ?>
    <?=$form->input("username")?>

    <br />

    <?=$form->label("email", "Email");?>
    <?=$form->input("email");?>

    <br />

    <?=$form->label("password", "Password");?>
    <?=$form->input("password", array("type" => "password"));?>

    <br />

    <?=$form->input('submitForm', array('type' => 'submit', 'value' => 'Register'));?>
<?=$form->close();?>