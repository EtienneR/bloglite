<?= form_open(); ?>

	<div class='form-group'>
		<?= form_error('name'); ?>
		<?= form_label('Pseudo', 'name'); ?>
		<?= form_input($name); ?>
	</div>

	<div class='form-group'>
		<?= form_error('password'); ?>
		<?= form_label('Password (<a href="' . base_url('admin/reset') . '">Mot de passe oublié</a>)', 'password'); ?>
		<?= form_input($password); ?>
	</div>

	<input type='submit' value='Connexion' class='btn btn-default' />

<?= form_close(); ?>


