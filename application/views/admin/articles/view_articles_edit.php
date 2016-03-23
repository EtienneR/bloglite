<?= form_open() ?>
	<div class="row">
		<div class="col-md-6">
			<?= form_submit($submit) ?>
		</div>
		<?php if (isset($id)): ?>
		<div class="col-md-6">
			<div class="text-right">
				<i class="glyphicon glyphicon-calendar"></i> Rédigé <?= date_fr(nice_date($cdate, 'd'), nice_date($cdate, 'm'), nice_date($cdate, 'Y'), nice_date($cdate, 'H'), nice_date($cdate, 'i')) ?>
				<a href="<?= base_url('admin/articles/delete/' . $id) ?>">
				<span class="btn btn-warning">
					<i class="glyphicon glyphicon-trash"></i> Supprimer cet article
					</span>
				</a>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<?= validation_errors('<div class="alert alert-warning" role="alert">', '</div>') ?>

	<?php
	$array_state = array(0 => "Brouillon", "Publié");
	foreach ($array_state as $key => $value): ?>
	<div class="radio">
		<label for="<?php echo strtolower($value); ?>">
			<input type="radio" id="<?php echo strtolower($value); ?>" name="state" value="<?php echo $key; ?>" <?php if (isset($state) and $state == $key or set_value('state') == $key) echo 'checked="checked"'; ?> />
			<?php echo $value; ?>
		</label>
	</div>
	<?php endforeach; ?>

	<div class="row">

		<div class="form-group col-md-4">
			<?= form_label('Titre', 'title'); ?>
			<?= form_input($title); ?>
		</div>

		<div class="form-group col-md-4">
			<?= form_label('Tag(s) (séparés par un point virgule)', 'tags'); ?>
			<?= form_input($tags); ?>
		</div>

		<?php if (isset($id)): ?>
		<div class="form-group col-md-4">
			<?= form_label('Slug', 'slug'); ?>
			<?= form_input($slug); ?>
		</div>
		<?php endif; ?>

	</div>

	<div class="form-group">
		<?= form_label('Content', 'content'); ?>
		<?= form_textarea($content); ?>
	</div>

	<?= form_submit($submit) ?>

<?= form_close() ?>