<p>
	<a class="btn btn-primary" href="<?= base_url('admin/pages/edit') ?>">Ajouter une page</a>
</p>

<?php if (isset($pages) && $pages->num_rows() > 0): ?>
<table class="table table-hover">
	<tr>
		<th>#</th>
		<th>Titre</th>
		<th>Contenu</th>
		<th>Statut</th>
		<th>Auteur</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	<?php foreach ($pages->result() as $page): ?>
	<tr>
		<td><?= $page->articleId; ?></td>
		<td>
			<a href="<?= base_url('admin/pages/edit/' . $page->articleId) ?>" title="Modifier cet article">
				<?= $page->title; ?>
			</a>
		</td>
		<td><?= strip_tags(character_limiter($this->markdown->parse($page->content), 128)) ?></td>
		<td>
			<?php if ($page->state == 0): ?>
			<a href="<?= base_url('admin/pages?status=draft') ?>">
				<span class="label label-warning">Brouillon</span>
			</a>
			<?php else: ?>
			<a href="<?= base_url('admin/pages?status=published') ?>">
				<span class="label label-info">Publié</span>
			</a>
			<?php endif; ?>
		</td>
		<td>
			<a href="<?= base_url('admin/pages/user/' . $page->userId) ?>">
				<?= $page->name ?>
			</a>
		</td>
		<td>
			<a href="<?= base_url($page->slug) ?>" target="_blank" title="Aperçu">
				<i class="glyphicon glyphicon-eye-open"></i>
			</a>
		</td>
		<td class="text-center">
			<a href="<?= base_url('admin/pages/edit/' . $page->articleId) ?>" title="Modifier cet article">
				<i class="glyphicon glyphicon-pencil"></i>
			</a>
		</td>
		<td class="text-center">
			<a href="<?= base_url('admin/pages/delete/' . $page->articleId) ?>" 
			   title="Supprimer cet article"
			   onclick="return deleteConfirm('page')">
				<i class="glyphicon glyphicon-trash"></i>
			</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<p class="text-right">
	<?= $pages->num_rows(); ?> page<?php if ($pages->num_rows() > 1): ?>s<?php endif; ?>
</p>

<?php else: ?>
<h1 class="text-center">Aucune page</h1>
<?php endif; ?>