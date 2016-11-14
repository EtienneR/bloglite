<div class="col-md-4 col-md-offset-4">

	<?= form_open(); ?>

		<div class="form-group">
			<?= form_error('name'); ?>
			<?= form_label('Pseudo', 'name'); ?>
			<?= form_input($name); ?>
		</div>

		<div class="form-group">
			<?= form_error('password'); ?>
			<?= form_label('Password', 'password'); ?>
			<?= form_input($password); ?>
		</div>

		<input type="submit" value="Connexion" class="btn btn-lg btn-primary col-md-12" />

	<?= form_close(); ?>

	<p class="col-md-12 text-center">
		<a href="<?= base_url('admin/reset') ?>">Mot de passe oublié</a>
	</p>

</div>