<h1><?= $title ?></h1>

<form method="post" action="<?= $base_url ?>/user/store">
    <input type="text" name="name" placeholder="Nome" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    
    <button type="submit">Salvar</button>
</form>

