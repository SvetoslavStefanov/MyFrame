<h4><?=$page->title?></h4>
<br />
<?=$page->content?>
<br />
<?=link_to('InfoPage/edit/'. $page->id, 'Edit')?>
 &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
<?=link_to('InfoPage/destroy/'. $page->id, 'Delete')?>