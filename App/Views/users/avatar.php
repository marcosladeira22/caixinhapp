<h2>Alterar avatar</h2>

<form method="post" action="<?= $base_url ?>/user/uploadAvatar" enctype="multipart/form-data">

    <input type="file" name="avatar" required>

    <br><br>

    <button type="submit">Salvar</button>
</form>

<?php if (!empty($_SESSION['user']['avatar'])): ?>
    <p>Avatar atual:</p>
    <img src="<?= $base_url ?>/uploads/avatars/<?= $_SESSION['user']['avatar']; ?>" width="120">
<?php endif; ?>
