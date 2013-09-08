<?=nl2br($article->content);?>

<br />
<?=link_to("Article/edit/{$article->id}", "edit");?>
&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
<?=link_to("Article/destroy/{$article->id}", "delete");?>