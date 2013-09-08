<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="<?= isset($seo_description) ? $seo_description : '' ?>" />
        <meta name="keywords" content="<?= isset($seo_keywords) ? $seo_keywords : '' ?>" />
        <meta property="og:title" content="<?= $title ?> | Site Title"/>
        <meta property="og:site_name" content="Site Description for social media"/>
        <meta property="og:description" content="<?= isset($seo_description) ? $seo_description : '' ?>"/>
        <? $social_pages_img = isset($social_pages_img) ? $social_pages_img : "Your default image"; ?>
        <meta property="og:image" content= "<?= $social_pages_img ?>" />
        <title><?= $title ?> | Site Title</title>
        <link rel="stylesheet" href="/public_html/styles/main.css" type="text/css" />

    </head>
    <body>
        <? $data = array($controllerName, $actionName, $id); ?>
        <div id="container">
            <ul class="menu">
                <li class="<?= choseTab(array('Article', 'index'), $data) ?>">
                    <div class="nav-homepage"></div>
                    <?= link_to(' ', 'Home') ?>
                </li>
                <? if (isset($currentUser)): ?>
                    <li class="<?= choseTab(array('Article', 'new'), $data) ?>">
                        <?= link_to('article/new', 'Create new Article') ?>
                    </li>
                    <li class="<?= choseTab(array('admin_Sign', 'login'), $data) ?>">
                        <?= link_to('admin/Sign/in', 'Login in administration', array('target' => '_blank')) ?>
                    </li>
                <? endif; ?>
            </ul>
            <div style="float: right;">
                <? if (isset($currentUser)): ?>
                    User: <?= $currentUser->username ?>
                    <?= link_to('sign/out', 'Logout') ?>
                <? elseif ($controllerName != 'sign'): ?>
                    <?= link_to('Sign/in', 'Login') ?>
                <? endif; ?>
            </div>
            <br class="fantom" />
            <h1><?= $title ?></h1>
            <?php include VIEWS_DIR . '/' . $this->template . '.php'; ?>

            <script type="text/javascript" src="/scripts/index.js"></script>
        </div>
    </body>
</html>