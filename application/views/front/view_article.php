<?php if (isset($draft)): ?>
<div class="callout warning">
  Cet article n'est pas publié sur votre site, la page retourne ainsi une erreur HTTP 206.
</div>
<?php endif; ?>

<article>
	<h1 class="text-center"><?= $title ?></h1>
	<div class="text-center">
	<?php if (isset($demo)): ?>
		<a href="<?= $demo ?>" target="_blank" class="success button">Démo</a>
	<?php endif; ?>
	<?php if (isset($download)): ?>
		<a href="<?= $download ?>" target="_blank" class="button">Télécharger</a>
	<?php endif; ?>
	</div>
	<?php if ($type == 'article'): ?>
	<p><?= date_fr(nice_date($pdate, 'd'), nice_date($pdate, 'm'), nice_date($pdate, 'Y'), nice_date($pdate, 'H'), nice_date($pdate, 'i')) ?></p>
	<?php endif; ?>
	<?= getTags($tags, 'article'); ?>
	<?= $this->markdown->parse($content) ?>
</article>

<p><a class="btn btn-default" href="<?= base_url() ?>">Retour à l'accueil</a></p>

<?php if (!empty($articles) && $type == 'article'): ?>
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
