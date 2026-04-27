<?php
use Core\Sessao;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="<?= base_url('?rota=dashboard@index') ?>">
            CaixinhApp
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">

                <!-- Início -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('?rota=dashboard@index') ?>">
                        Início
                    </a>
                </li>

                <!-- Criar Grupo -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('?rota=grupo@criar') ?>">
                        Criar Grupo
                    </a>
                </li>

                <!-- Criar Usuário -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Criar Usuário
                    </a>
                </li>
            </ul>

            <!-- Usuário logado -->
            <?php
                $nomeUsuario = Sessao::get('usuario_nome');
            ?>
            <?php if ($nomeUsuario): ?>
                <a href="<?= base_url('?rota=usuario@perfil') ?>" class="nav-link navbar-text text-light">
                    <?= htmlspecialchars($nomeUsuario) ?>
                </a>  
                &nbsp;<span class="navbar-text">|</span>&nbsp;
                <a href="<?= base_url('?rota=auth@logout') ?>" class="nav-link navbar-text text-danger">Sair</a>
            <?php endif; ?>
        </div>
    </div>
</nav>