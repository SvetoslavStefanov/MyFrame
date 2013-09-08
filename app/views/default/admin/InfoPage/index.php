<? foreach($pages as $page): ?>
    <hr />
    <?=$page->title?>
    <br />
    <?=nl2br(substr($page->content, 0, 500) . " ..."); ?>
    <br />

    <?=link_to("InfoPage/edit/{$page->id}", "Edit");?>
    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
    <?=link_to("InfoPage/destroy/{$page->id}", "Delete");?>
<? endforeach; ?>
