<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta description="<?= $description ?>" />
	<meta property="og:site_name" content="<?= $name_site ?>" /> 
	<meta property="og:title" content="<?= $title_page ?>" /> 
	<meta property="og:description" content="<?= $description ?>" /> 
	<title><?= $title_page ?></title>
	<?= css_url('foundation.min') ?>
	<style>
	body{
		background: #eaeaea;
	}
	</style>
</head>
<body>

	<div class="row" role="header">

		<div class="large-10 columns">
			<?php if ($this->uri->total_segments() == 0 && !isset($_GET['q']) && $this->uri->total_segments() == 1 && $this->uri->segments['1'] == 'page'): ?>
			<h1 style="font-size: 1em;"><?= $name_site ?></h1>
			<?php else: ?>
			<p>
				<a href="<?= base_url() ?>"><?= $name_site ?></a>
			</p>
			<?php endif; ?>
		</div>

	</div>


	<div class="row">

		<div class="large-10 columns" style="background: #fff">
			<?= $contents ?>
		</div>

		<asside class="large-2 columns">
			<?php if (isset($tags_list)): ?>
				<div class="menu-centered">
			<ul class="menu vertical">
			<?php asort($tags_list); ?>
			<?php foreach($tags_list as $tag): ?>
				<?php if(!empty($tag)): ?>
				<li>
					<a href="<?= base_url('tag/' . $tag) ?>"><?= $tag ?></a>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
			</div>
			<?php endif; ?>
		</asside>

	</div>

	<footer style="position: relative; margin-top: 60px; display: block;">
		<nav style="background: #333; width: 100%; bottom: 0; padding: 5px 0; position: fixed;">
			<div class="row">
				<div class="large-9 medium-9 small-12 columns">
					<ul class="menu">
						<li <?php if ($this->uri->total_segments() === 0 || $this->uri->segments['1'] == 'page'): ?>class="active"<?php endif; ?>>
							<a href="<?= base_url() ?>">Home</a>
						</li>
						<li <?php if ($this->uri->total_segments() === 1 && $this->uri->segments['1'] == 'archives'): ?>class="active"<?php endif; ?>>
							<a href="<?= base_url('archives') ?>">Archives</a>
						</li>
						<li <?php if ($this->uri->total_segments() === 1 && $this->uri->segments['1'] == 'contact'): ?>class="active"<?php endif; ?>>
							<a href="<?= base_url('contact') ?>">Contact</a>
							</li>
						<li>
							<a href="<?= base_url('feed') ?>">Feed</a>
						</li>
					</ul>
				</div>
				<div class="large-3 medium-3 small-12 columns">
					<form class="menu" action="<?= base_url() ?>" method="GET">
						<input type="search" name="q" placeholder="Rechercher" required>
					</form>
				</div>
			</div>
		</nav>
	</footer>

	<?php if ( $this->uri->total_segments() == 1 && $this->uri->segments['1'] == 'contact'): ?>
	<?= js_url('jquery-2.2.0.min') ?>
	<?= js_url('foundation.min') ?>
	<?php endif; ?>

</body>
</html>