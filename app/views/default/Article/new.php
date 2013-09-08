<?=$form = new FormBuilder($article)?>
    <p>
        <?=$form->label("title", "Title");?>
        <br />
        <?=$form->input("title");?>
    </p>

    <p>
        <?=$form->label("content", "Article's content")?>
        <br />
        <?=$form->textarea("content", array("rows" => 15, "cols" => 0));?>
    </p>

    <?=$form->input('submitArtcle', array('type' => 'submit', 'value' => 'Submit'))?>
<?=$form->close()?>