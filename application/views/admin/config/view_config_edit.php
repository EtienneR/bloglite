<?= form_open() ?>


	<?= validation_errors('<div class="alert alert-warning" role="alert">', '</div>') ?>

	<div class="row">
		<div class="form-group col-md-4">
			<?= form_label('Titre', 'title'); ?>
			<?= form_input($name_site); ?>
		</div>

		<div class="form-group col-md-4">
			<?= form_label('Email', 'email'); ?>
			<?= form_input($email); ?>
		</div>

		<div class="form-group col-md-1">
			<?= form_label('Pagination', 'pagination'); ?>
			<?= form_input($pagination); ?>
		</div>

	</div>

	<div class="row">
		<div class="form-group col-md-4">
			<?= form_label('Description', 'description'); ?>
			<?= form_textarea($description); ?>
		</div>
	</div>

	<div class="row">

		<div class="form-group col-md-4">
			<?= form_label('Slugs réservés', 'slugs_reserved'); ?>
			<?= form_input($slugs_reserved); ?>
		</div>
	</div>

	<?= form_submit($submit) ?>

<?= form_close() ?>