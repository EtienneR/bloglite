<?php if (isset($search) && $search->num_rows() > 0): ?>
<ul class="nav nav-tabs">
	<li role="presentation" <?php if ($this->uri->total_segments() == 2): ?>class="active"<?php endif; ?>>
		<a href="<?= base_url('admin/search'); ?>">
			Données brutes
		</a>
	</li>
	<li role="presentation" <?php if ($this->uri->total_segments() == 3): ?>class="active"<?php endif; ?>>
		<a href="<?= base_url('admin/search/occurrences'); ?>">
			Occurrences
		</a>
	</li>
</ul>

<table class="table table-hover">
	<tr>
		<?php if ($this->uri->total_segments() == 2): ?>
		<th>#</th>
		<?php endif; ?>
		<th>Mots</th>
		<?php if ($this->uri->total_segments() == 2): ?>
		<th>Score</th>
		<th>Date</th>
		<?php else: ?>
		<th>Nombre</th>
		<?php endif; ?>
	</tr>
	<?php foreach ($search->result() as $word): ?>
	<tr>
		<?php if ($this->uri->total_segments() == 2): ?>
		<td><?= $word->searchId; ?></td>
		<?php endif; ?>
		<td><?= $word->name; ?></td>
		<?php if ($this->uri->total_segments() == 2): ?>
		<td><?= $word->score; ?></td>
		<td><?= $word->date; ?></td>
		<?php else: ?>
		<td><?= $word->occurence; ?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</table>

<p class="text-right">
	<?= $search->num_rows(); ?> mot<?php if ($search->num_rows() > 1): ?>s<?php endif; ?>
</p>

<?php else: ?>
<h1 class="text-center">Aucun mot</h1>
<?php endif; ?>