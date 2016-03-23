<p>
	<?php if ($connected['level'] == 0): ?>
	<a class="btn btn-primary" href="<?= base_url('admin/users/signup') ?>">Ajouter un utilisateur</a>
	<?php endif; ?>
	<a class="btn" href="<?= base_url('admin/users/edit/' . $connected['id']) ?>">Editer mon profil</a>
</p>

<?php if ($users->num_rows() > 0): ?>
<table class="table table-hover">
	<tr>
		<th>#</th>
		<th>Name</th>
		<th>Niveau</th>
		<th>Email</th>
		<?php if ($connected['level'] == 0): ?>
		<th></th>
		<th></th>
		<?php endif; ?>
	</tr>
	<?php foreach ($users->result() as $user): ?>
	<tr>
		<td><?= $user->userId ?></td>
		<td><?= $user->name ?></td>
		<td>
			<?php if ($user->level == 0): ?>
			Admin
			<?php else: ?>
			Modérateur
			<?php endif; ?>
		</td>
		<td><?= $user->email ?></td>
		<?php if ($connected['level'] == 0): ?>
		<td class="text-center">
			<a href="<?= base_url('admin/users/edit/' . $user->userId) ?>" title="Modifier cet utilisateur">
				<i class="glyphicon glyphicon-pencil"></i>
			</a>
		</td>
		<td class="text-center">
			<a href="<?= base_url('admin/users/delete/' . $user->userId) ?>" title="Supprimer cet utilisateur">
				<i class="glyphicon glyphicon-trash"></i>
			</a>
		</td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</table>

<p class="text-right">
	<?= $users->num_rows(); ?> utilisateur<?php if ($users->num_rows() > 1): ?>s<?php endif; ?>
</p>

<?php else: ?>
	<p class="text-center">Aucun utilisateur</p>
<?php endif; ?>