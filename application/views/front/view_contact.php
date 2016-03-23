<h1 class="text-center">Contact</h1>

<?php if (empty($success)): ?>
<?= form_open(); ?>

    <div>
        <?= form_error("subject"); ?>
        <?= form_label("Sujet", "subject"); ?>
        <?= form_input($subject); ?>
    </div>

    <div>
        <?= form_error("name"); ?>
        <?= form_label("Nom", "name"); ?>
        <?= form_input($name); ?>
    </div>

    <div>
        <?= form_error("email"); ?>
        <?= form_label("Email", "email"); ?>
        <?= form_input($email); ?>
    </div>

    <div>
        <?= form_error("message"); ?>
        <?= form_label("Message", "message"); ?>
        <?= form_textarea($message); ?>
    </div>

    <p>Tous les champs sont obligatoires</p>

    <?= form_submit($submit); ?>

<?= form_close(); ?>

<?php else: ?>
<div class="success callout">
    <p>Votre message a été envoyé. Nous vous répondrons dès que possible.</p>
</div>
<?php endif; ?>