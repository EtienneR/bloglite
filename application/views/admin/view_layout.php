<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?= $title_page ?> - Dashboard</title>
	<?= css_url('bootstrap.min') ?>
	<?php if ($this->uri->total_segments() >= 3 && $this->uri->segments['2'] === 'articles' && $this->uri->segments['3'] === 'edit'):?>
	<?= css_url('simplemde.min') ?>
	<?php endif; ?>
	<?= css_url('bootstrap-tokenfield.min') ?>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />

	<?= js_url('jquery-2.2.0.min') ?>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<?= js_url('bootstrap.min') ?>
	<?= js_url('bootstrap-tokenfield.min') ?>
	<?= css_url('jquery.flexdatalist.min') ?>

	<?php if ($this->uri->total_segments() >= 3 && $this->uri->segments['2'] === 'articles' && $this->uri->segments['3'] === 'edit'):?>
	<link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
	<script src="https://unpkg.com/flatpickr"></script>
	<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
	<?php endif; ?>
</head>
<body class="container-fluid">

	<nav class="navbar navbar-default">

		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= base_url('admin') ?>">Dashboard</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<?php if (isset($connected)): ?>
				<ul class="nav navbar-nav">
					<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'articles'):?>class="active"<?php endif; ?>>
						<a href="<?= base_url('admin') ?>">Articles</a>
					</li>
					<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'pages'):?>class="active"<?php endif; ?>>
						<a href="<?= base_url('admin/pages') ?>">Pages</a>
					</li>
					<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'users'):?>class="active"<?php endif; ?>>
						<a href="<?= base_url('admin/users') ?>">Utilisateurs</a>
					</li>
				</ul>
				<?php endif; ?>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?= base_url() ?>" target="_blank">Le blog</a></li>
					<?php if (isset($connected)): ?>
<!-- 					<?php if ($connected['level'] == 0): ?>
					<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'config'): ?>class="active"<?php endif; ?>>
						<a href="<?= base_url('admin/config') ?>">Config</a>
					</li>
					<?php endif; ?>
					<li <?php if ($this->uri->total_segments() == 4 && $this->uri->segments['4'] == $connected['id']): ?>class="active"<?php endif; ?>>
						<a href="<?= base_url('admin/users/edit/' . $connected['id']) ?>"><?= $connected['username'] ?></a>
					</li>
					<li>
						<a href="<?= base_url('admin/logout') ?>">Logout</a>
					</li> -->

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $connected['username'] ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php if ($connected['level'] == 0): ?>
							<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'config'): ?>class="active"<?php endif; ?>>
								<a href="<?= base_url('admin/config') ?>">
									<i class="glyphicon glyphicon-cog"></i> Paramêtres
								</a>
							</li>
							<?php endif; ?>
							<li <?php if ($this->uri->total_segments() == 4 && $this->uri->segments['4'] == $connected['id']): ?>class="active"<?php endif; ?>>
								<a href="<?= base_url('admin/users/edit/' . $connected['id']) ?>">
									<i class="glyphicon glyphicon glyphicon-user"></i> Profil
								</a>
							</li>
							<li <?php if ($this->uri->total_segments() >= 2 && $this->uri->segments['2'] === 'search'):?>class="active"<?php endif; ?>>
								<a href="<?= base_url('admin/search') ?>">
									<i class="glyphicon glyphicon-search"></i> Mots recherches
								</a>
							</li>
							<li>
								<a href="<?= base_url('admin/logout') ?>">
									<i class="glyphicon glyphicon-off"></i> Déconnexion
								</a>
							</li>
						</ul><!-- end .dropdown-menu -->
					</li>


					<?php endif; ?>
				</ul>
			</div><!-- /.navbar-collapse -->

		</div><!-- /.container-fluid -->

	</nav>

	<?php if ($this->session->flashdata("success")): ?>
	<div class="alert alert-success">
		<?= $this->session->flashdata("success") ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
	</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata("warning")): ?>
	<div class="alert alert-warning">
		<?= $this->session->flashdata("warning") ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
	</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata("danger")): ?>
	<div class="alert alert-danger">
		<?= $this->session->flashdata("danger") ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
	</div>
	<?php endif; ?>

	<?= $contents ?>

	<footer class="col-md-12">
		<p class="text-center">
			BlogLite propulsé par CodeIgniter <?= CI_VERSION ?> et SQLite 3 
			<?php if (isset($connected)): ?>
			- <a href="<?= base_url('admin/config/about') ?>">A propos</a>
			<?php endif; ?>
		</p>
	</footer>


	<?php if ($this->uri->total_segments() >= 3 && $this->uri->segments['2'] === 'articles' && $this->uri->segments['3'] === 'edit'):?>
	<?= js_url('simplemde.min') ?>
	<?= js_url('jquery.flexdatalist.min') ?>
	<script>
		var simplemde = new SimpleMDE({ 
			element: document.getElementById('content') 
		});
	</script>
	<?php endif; ?>
	<script>
		function deleteConfirm(type) {
			if (type == 'article') {
				var message = 'cet article';
			} else if (type == 'page') {
				var message = 'cette page';
			} else if (type == 'user') {
				var message = 'cet utilisateur';
			}

			if (message) {
				var a = confirm('Etes-vous sur de vouloir supprimer ' + message + ' ?!');
				if (a) {
					return true;
				} else{
					return false;
				}
			}
		}
	</script>

</body>
</html>