<h1><?= $title_page ?></h1>
<?php if ($articles->num_rows() > 0): ?>
<ul>
	<?php foreach ($articles->result() as $article): ?>
	<li>
		<a href="<?= base_url($article->slug) ?>">
			<?= $article->title; ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<p>Aucun article</p>
<?php endif; ?>