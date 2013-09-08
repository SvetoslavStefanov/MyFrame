<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $title ?> | Administration</title>
        <meta charset="UTF-8">
            <meta name="description" content="Description" />
            <link rel="stylesheet" type="text/css" href="/public_html/styles/main.css" media="all" />
    </head>
    <body>
        <div id="container">
            <? if (isset($adminUser)): ?>
                <ul class="menu">
                    <li class="<?= choseTab(array('admin_Dashboard', 'home'), $data) ?>">
                        <?= link_to('Dashboard/home', 'Dashboard') ?>
                    </li>
                    <li class="<?= choseTab(array('InfoPage', 'index'), $data) ?>">
                        <?= link_to('InfoPage/index', 'List all InfoPages') ?>
                    </li>
                    <li class="<?= choseTab(array('InfoPage', 'new'), $data) ?>">
                        <?= link_to('InfoPage/new', 'Create new Info Page') ?>
                    </li>
                    <li class="<?= choseTab(array('FormValidations', 'index'), $data) ?>">
                        <?= link_to('FormValidations/index', 'List Validations') ?>
                    </li>
                </ul>
                <div style="float: right;">
                    <? if (isset($adminUser)): ?>
                        Administrator: <?= $adminUser->username ?>
                        <?= link_to('sign/out', 'Logout') ?>
                    <? elseif ($controllerName != 'sign'): ?>
                        <?= link_to('Sign/in', 'Login') ?>
                    <? endif; ?>
                </div>
                <br class="fantom" />
                <h1><?= $title ?></h1>
            <? endif; ?>

            <?php include VIEWS_DIR . '/' . str_replace("_", "/", $this->template) . '.php'; ?>
            <script type="text/javascript" src="/scripts/index.js"></script>
        </div>
    </body>
</html>
