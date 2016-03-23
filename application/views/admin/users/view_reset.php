<?= form_open(); ?>

	<div class='form-group'>
		<?= form_error('email'); ?>
		<?= form_label('Email', 'email'); ?>
		<?= form_input($email); ?>
	</div>

	<input type="submit" value="Valider" class="btn btn-primary" />

<?= form_close(); ?>


