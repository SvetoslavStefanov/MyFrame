<?= $form = new Formbuilder($page, array('enctype' => "multipart/form-data")) ?>
    <p>
        <?= $form->label('title', 'Title') ?>
        <br />
        <?= $form->input('title') ?>
    </p>
    <p>
        <?= $form->label('content', 'Content') ?>
        <br />
        <?=$form->textarea("content", array("rows" => 20, "cols" => 50)); ?>
    </p>
    <p>
        <?= $form->label('status', 'Hidden') ?>
        <?= $form->input('status', array('type' => 'radio', 'value' => InfoPage::INFOPAGE_STATUS_HIDDEN)) ?>

        <?= $form->label('status', 'Visible') ?>
        <?= $form->input('status', array('type' => 'radio', 'value' => InfoPage::INFOPAGE_STATUS_VISIBLE, 'checked' => 'checked')) ?>

        <?= $form->label('status', 'Inactive') ?>
        <?= $form->input('status', array('type' => 'radio', 'value' => InfoPage::INFOPAGE_STATUS_INACTIVE)) ?>
    </p>
    <br />
    <p>
        <?= $form->label('seo_description', 'Seo Description') ?>
        <br />
        <?= $form->textarea('seo_description') ?>
    </p>
    <p>
        <?= $form->label('seo_keywords', 'Seo keywords') ?>
        <br />
        <?= $form->textarea('seo_keywords') ?>
    </p>

    <?= $form->input('submit_page', array('type' => 'submit', 'value' => 'Save', 'class' => 'blue')) ?>
<?= $form->close() ?>


