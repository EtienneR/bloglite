<?= form_open() ?>

	<?= validation_errors('<div class="alert alert-warning" role="alert">', '</div>') ?>

	<!-- Date de planification -->
	<div class="row">
		<div class="form-group col-md-2">
			<?= form_label('Date de publication', 'pdate2'); ?>
			<?= form_input($pdate); ?>
			<input type="hidden" id="pdate" name="pdate">
		</div>
	</div>

	<!-- Onglets -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#home" aria-controls="home" role="tab" data-toggle="tab">Infos</a>
		</li>
		<li role="presentation">
			<a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Contenu</a>
		</li>
		<li role="presentation">
			<a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Options</a>
		</li>
		<li>
			<?= form_submit($submit) ?>
		</li>

		<?php if (isset($id)): ?>
			<div class="text-right">
				<i class="glyphicon glyphicon-calendar"></i> Rédigé <?= date_fr(nice_date($cdate, 'd'), nice_date($cdate, 'm'), nice_date($cdate, 'Y'), nice_date($cdate, 'H'), nice_date($cdate, 'i')) ?>
				<a href="<?= base_url('admin/articles/delete/' . $id) ?>">
				<span class="btn btn-warning">
					<i class="glyphicon glyphicon-trash"></i> Supprimer cet article
					</span>
				</a>
			</div>
		<?php endif; ?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">

		<div role="tabpanel" class="tab-pane active" id="home">
			<?php
				$array_state = array(0 => "Brouillon", "Publié");
				foreach ($array_state as $key => $value): 
			?>
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
					<?= form_label('Tag(s) (séparés par une virgule)', 'tags'); ?>
					<?= form_input($tags); ?>
				</div>

				<datalist id="tagsList">
					<?php foreach (array_unique($tagInArray) as $tag): ?>
						<option value="<?= $tag ?>"><?= $tag; ?></option>
					<?php endforeach; ?>
				</datalist>

				<?php if (isset($id)): ?>
				<div class="form-group col-md-4">
					<?= form_label('Slug', 'slug'); ?>
					<?= form_input($slug); ?>
				</div>
				<?php endif; ?>

			</div>
    	</div>

		<div role="tabpanel" class="tab-pane" id="messages">
			<div class="form-group">
				<?= form_label('Content', 'content'); ?>
				<?= form_textarea($content); ?>
			</div>
   		</div>

    	<div role="tabpanel" class="tab-pane" id="settings">
			<div class="row">

				<div class="form-group col-md-4">
					<?= form_label('Démo', 'demo'); ?>
					<?= form_input($demo); ?>
				</div>

				<div class="form-group col-md-4">
					<?= form_label('Download', 'download'); ?>
					<?= form_input($download); ?>
				</div>

			</div>
		</div>

  	</div>
  
<?= form_close() ?>


<script>
Flatpickr.localize(Flatpickr.l10ns.fr);
flatpickr(".flatpickr", {
	utc: true,
	dateFormat: 'm/d/Y H:m:S',
	enableTime: true,
	time_24hr: true,
	onClose: function(selectedDates, dateStr, instance) {
		function toTimestamp(strDate){
			var datum = Date.parse(strDate);
			return datum/1000;
		}
		document.getElementById('pdate').value = toTimestamp(dateStr);
	}
});

/*$('#tags').tokenfield({
  autocomplete: {
    source: [<?php sort($tagInArray); foreach (array_unique($tagInArray) as $row) {
				if ($row === end($tagInArray)) {
					echo "'" . $row . "'";
				} else {
					echo "'" . $row . "',";
				}
			} ?>],
    delay: 0
  },
  showAutocompleteOnFocus: true
});
*/
$('.flexdatalist').flexdatalist({
     minLength: 1
});
</script>