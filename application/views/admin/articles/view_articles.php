<p>
	<a class="btn btn-primary" href="<?= base_url('admin/articles/edit') ?>">Ajouter un article</a>
	<?php if ($this->uri->segment(4) == $connected['id'] || isset($_GET['status']) ): ?>
		<a class="btn" href="<?= base_url('admin/articles') ?>">Tous les articles</a>
	<?php else: ?>
		<?php if ($number_articles > 0): ?>
			<a class="btn" href="<?= base_url('admin/articles/user/' . $connected['id']) ?>">Mes articles (<?= $number_articles ?>)</a>
		<?php endif; ?>
	<?php if (isset($_GET['tag'])) :?>
		<a class="btn btn-default" href="<?= base_url('admin') ?>">Tous les articles</a>
	<?php endif; ?>
	<?php endif; ?>
</p>

<?php if (isset($articles) && $articles->num_rows() > 0): ?>
<table class="table table-hover">
	<tr>
		<th>#</th>
		<th>Titre</th>
		<th>Contenu</th>
		<th>Statut</th>
		<th>Création</th>
		<th>Publication</th>
		<th>Tags</th>
		<th>Auteur</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	<?php foreach ($articles->result() as $article): ?>
	<tr>
		<td><?= $article->articleId; ?></td>
		<td>
			<a href="<?= base_url('admin/articles/edit/' . $article->articleId) ?>" title="Modifier cet article">
				<?= $article->title; ?>
			</a>
		</td>
		<td><?= strip_tags(character_limiter($this->markdown->parse($article->content), 128)) ?></td>
		<td>
			<?php if ($article->state == 0): ?>
			<a href="<?= base_url('admin/articles?status=draft') ?>">
				<span class="label label-warning">Brouillon</span>
			</a>
			<?php else: ?>
			<a href="<?= base_url('admin/articles?status=published') ?>">
				<span class="label label-info">Publié</span>
			</a>
			<?php endif; ?>
		</td>
		<td>
			<?= date_fr(nice_date($article->cdate, 'd'), nice_date($article->cdate, 'm'), nice_date($article->cdate, 'Y'), nice_date($article->cdate, 'H'), nice_date($article->cdate, 'i')) ?>
		</td>
			<?php if ($article->pdate >= date(DATE_ISO8601, time())): ?>
				<td class="alert-warning">
			<?php else: ?>
				<td class="alert-success">
			<?php endif; ?>
			<?= date_fr(nice_date($article->pdate, 'd'), nice_date($article->pdate, 'm'), nice_date($article->pdate, 'Y'), nice_date($article->pdate, 'H'), nice_date($article->pdate, 'i')) ?>
		</td>
		<td>
			<?= getTags($article->tags, 'admin') ?>
		</td>
		<td>
			<a href="<?= base_url('admin/articles/user/' . $article->userId) ?>">
				<?= $article->name ?>
			</a>
		</td>
		<td>
			<a href="<?= base_url($article->slug) ?>" target="_blank" title="Aperçu">
				<i class="glyphicon glyphicon-eye-open"></i>
			</a>
		</td>
		<td class="text-center">
			<a href="<?= base_url('admin/articles/edit/' . $article->articleId) ?>" title="Modifier cet article">
				<i class="glyphicon glyphicon-pencil"></i>
			</a>
		</td>
		<td class="text-center">
			<?php if ($connected['level'] == 0 || ($article->userId == $connected['id'])): ?>
			<a href="<?= base_url('admin/articles/delete/' . $article->articleId) ?>" 
			   title="Supprimer cet article" 
			   onclick="return deleteConfirm('article')">
				<i class="glyphicon glyphicon-trash"></i>
			</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<p class="text-right">
	<?= $articles->num_rows(); ?> article<?php if ($articles->num_rows() > 1): ?>s<?php endif; ?>
</p>

<?php else: ?>
<h1 class="text-center">Aucun article</h1>
<?php endif; ?>