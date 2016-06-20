<p>
	<a class="btn btn-primary" href="<?= base_url('admin/articles/edit') ?>">Ajouter un article</a>
	<?php if ($this->uri->segment(4) == $connected['id'] || isset($_GET['status']) ): ?>
		<a class="btn" href="<?= base_url('admin/articles') ?>">Tous les articles</a>
	<?php else: ?>
		<?php if ($number_articles > 0): ?>
			<a class="btn" href="<?= base_url('admin/articles/user/' . $connected['id']) ?>">Mes articles (<?= $number_articles ?>)</a>
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
		<?php
		if ($article->tags):
			$explode = explode(";", $article->tags);
			foreach ($explode as $tag):
		?>
			<a href="<?= base_url('admin/articles?tag=' . $tag) ?>"><?= $tag ?></a>
		<?php
			endforeach;
		endif;
		?>
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
			<a href="<?= base_url('admin/articles/delete/' . $article->articleId) ?>" title="Supprimer cet article">
				<i class="glyphicon glyphicon-trash"></i>
			</a>
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

<!-- <div ng-app="articlesApp">
	<div ng-controller="ArticlesListCtrl">
		<table class="table table-hover">
			<tr>
				<th>#</th>
				<th>Titre</th>
				<th>Contenu</th>
				<th>Statut</th>
				<th>Tags</th>
				<th>Auteur</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr ng-repeat="article in articles">
				<td>{{article.articleId}}</td>
				<td><a href="articles/edit/{{article.articleId}}" title="Modifier cet article">{{article.title}}</td>
				<td>{{article.content | limitTo : 128}}</td>
				<td>{{article.state}}</td>
				<td><a href="articles?tag={{article.tags}}">{{article.tags}}</td>
				<td>{{article.name}}</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
<script>
	var articlesApp = angular.module('articlesApp', []);

	articlesApp.controller('ArticlesListCtrl', function ($scope, $http) {
  		$http.get('http://localhost/admin/api/articles').success(function(data) {
    		$scope.articles = data;
  		});
	});
</script> -->