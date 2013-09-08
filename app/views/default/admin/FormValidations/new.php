<?=$form = new Formbuilder($formValidation);?>
    <p>
        <?=$form->label("name", "Form Name");?>
        <br />
        <?=$form->input("name");?>
    </p>
    <p>
        <?=$form->label("address1", "Address 1");?>
        <br />
        <?=$form->input("address1");?>
    </p>
    <p>
        <?=$form->label("address2", "Address 2");?>
        <br />
        <?=$form->input("address2");?>
    </p>

    <?=$form->input("submitForm", array("type" => "submit", "value" => "Save"));?>
<?=$form->close();?>