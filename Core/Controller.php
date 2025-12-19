<?php

// Define o namespace da classe
namespace Core;

// Classe base para todos os controllers
class Controller
{
    // Armazena a URL base do projeto
    protected $base_url;

    public function __construct()
    {
        // Carrega as configurações do app
        $config = require __DIR__ . '/../config/app.php';

        // Disponibiliza base_url para o controller
        $this->base_url = $config['base_url'];
    }

    // Método responsável por carregar views
    protected function view($view, $data = [])
    {
         // Disponibiliza base_url para as views
        $data['base_url'] = $this->base_url;

        // Transforma o array em variáveis
        // ['title' => 'Exemplo'] vira $title
        extract($data);

        // Inclui o header padrão
        require __DIR__ . "/../App/Views/layouts/header.php";

        // Inclui a view específica
        require __DIR__ . "/../App/Views/$view.php";

        // Inclui o footer padrão
        require __DIR__ . "/../App/Views/layouts/footer.php";
    }

    //método padrão de redirecionamento
    protected function redirect($path)
    {
        // Redireciona para a URL correta do projeto
        header("Location: {$this->base_url}{$path}");
        exit;
    }

    protected function auth()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['user'])) {
            // Se não estiver, redireciona para login
            $this->redirect('/login');
        }
    }

    // Define uma mensagem temporária (flash)
    protected function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    // Recupera e apaga a mensagem flash
    protected function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    // Valida campos obrigatórios de um formulário
    protected function validateRequired(array $fields)
    {
        // Array para armazenar mensagens de erro
        $errors = [];

        // Percorre cada campo obrigatório
        foreach ($fields as $field => $label) {

            // Se o campo não existir ou estiver vazio
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {

                // Adiciona a mensagem de erro
                $errors[] = "O campo {$label} é obrigatório.";
            }
        }

        // Retorna o array de erros (vazio se não houver erros)
        return $errors;
    }

    // Gera ou retorna o token CSRF da sessão
    protected function csrfToken()
    {
        // Se ainda não existir um token na sessão
        if (!isset($_SESSION['csrf_token'])) {

            // Gera um token aleatório e seguro
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Retorna o token
        return $_SESSION['csrf_token'];
    }

    // Valida o token CSRF enviado pelo formulário
    protected function validateCsrf()
    {
        // Se o token não existir no POST ou na sessão
        if (
            !isset($_POST['csrf_token']) ||
            !isset($_SESSION['csrf_token'])
        ) {
            return false;
        }

        // Compara os tokens de forma segura
        return hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        );
    }

}
