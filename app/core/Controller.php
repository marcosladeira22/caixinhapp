<?php

class Controller {

    // Método patrão para renderizar view dentro do layout
    protected function view($view, $dados = []) {

        // Extrai dados para variáveis
        extract($dados);

        // Caminho da view
        $view = __DIR__ . '/../views/' . $view . '.php';

        // Carrega layout principal
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}

?>