	<?php if (isset($id)): ?>
	<a href="<?= base_url('admin/users/delete/' . $id) ?>" title="Supprimer cet utilisateur">
		<i class="glyphicon glyphicon-trash"></i>
	</a>
	<?php endif; ?>

	<?= form_open(); ?>

	<?php if ($connected['level'] == 0 && !empty($options_level)): ?>
	<div class="row">
		<div class="form-group col-md-3">
			<?= form_label('Level', 'level') ?>
			<?= form_dropdown('level', $options_level, $level) ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="row">
		<div class="form-group col-md-3">
			<?= form_error('name'); ?>
			<?= form_label('Pseudo', 'name'); ?>
			<?= form_input($name); ?>
		</div>

		<div class="form-group col-md-3">
			<?= form_error('email'); ?>
			<?= form_label('Email', 'email'); ?>
			<?= form_input($email); ?>
		</div>
	</div>

	<div class="row">
		<?php if (isset($password)): ?>
		<div class="form-group col-md-3">
			<?= form_error('password'); ?>
			<?= form_label('Password ('. $password_length .' caractères minimum)', 'password'); ?>
			<?= form_input($password); ?>
		</div>
		<?php endif; ?>

		<?php if (isset($password_old)): ?>
		<div class="form-group col-md-3">
			<?= form_error('password_old'); ?>
			<?= form_label('Ancien password', 'password_old'); ?>
			<?= form_input($password_old); ?>
		</div>
		<?php endif; ?>

		<?php if (isset($password_new)): ?>
		<div class="form-group col-md-3">
			<?= form_error('password_new'); ?>
			<?= form_label('Nouveau password ('. $password_length . ' caractères minimum)', 'password_new'); ?>
			<?= form_input($password_new); ?>
		</div>
		<?php endif; ?>


		<div class="form-group col-md-3">
			<?= form_error('password_confirm'); ?>
			<?= form_label('Confirmation password','password_confirm'); ?>
			<?= form_input($password_confirm); ?>
		</div>
	</div>


		<input type="submit" value="Valider" class="btn btn-primary" />

	<?= form_close(); ?>
