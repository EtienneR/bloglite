<?php if (isset($_GET['q']) && $this->uri->total_segments() >= 0): ?>
<h1 class="text-center"><?= $title_page ?></h1>
<?php endif; ?>

<?php if (isset($articles)): ?>
<div class="row">
	<?php foreach ($articles as $article): ?>
	<article class="large-6 columns">
		<h2>
			<a href="<?= base_url($article->slug) ?>">
				<?= $article->title ?>
			</a>
		</h2>
		<p>
			<?= date_fr(nice_date($article->cdate, 'd'), nice_date($article->cdate, 'm'), nice_date($article->cdate, 'Y')); ?>
			<?= getTags($article->tags); ?>
		</p>
		<p><?= strip_tags(character_limiter($this->markdown->parse($article->content), 180)) ?></p>

	</article>
	<?php endforeach; ?>
</div>

<?php if (isset($pagination)): ?>
<div class="row">
	<div class="large-12 columns">
		<?= $pagination ?>
	</div>
</div>
<?php endif; ?>

<?php else: ?>
<h1>Aucun article n'est disponible.</h1>
<?php endif; ?>