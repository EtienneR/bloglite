<?php if (isset($draft)): ?>
<div class="callout warning">
  Cet article n'est pas publié sur votre site.
</div>
<?php endif; ?>

<article>
	<h1 class="text-center"><?= $title ?></h1>
	<?= date_fr(nice_date($cdate, 'd'), nice_date($cdate, 'm'), nice_date($cdate, 'Y'), nice_date($cdate, 'H'), nice_date($cdate, 'i')) ?>
	<?= getTags($tags); ?>
	<?= $this->markdown->parse($content) ?>
</article>

<p><a class="btn btn-default" href="<?= base_url() ?>">Retour à l'accueil</a></p>

<?php if (!empty($articles)): ?>
<?php if (count($articles) == 1): ?>
<p>Autre article :</p>
<?php else: ?>
<p>Autres articles :</p>
<?php endif; ?>
<ul>
	<?php foreach ($articles as $article): ?>
	<li>
		<a href="<?= $article->slug ?>"><?= $article->title ?></a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
