<? foreach($articles as $article): ?>
<hr style="width:400px;" align="left"/>
<h3><?=link_to("Article/show/{$article->id}", $article->title)?></h3>
<br />
<?=nl2br($article->content);?>
<? endforeach; ?>
