<?php

class AuthMiddleware {

    public static function verificar() {

        // Se usuário NÃO estiver logado
        if (!isset($_SESSION['usuario_id'])) {
            
            $_SESSION['erro'] = "Faça login para acessar";

            header("Location: " . BASE_URL . "/login");
            exit;
        }
    }
}

?>