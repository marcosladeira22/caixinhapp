<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {

    private $db;

    public function __construct() {
        
        //Instancia conexão
        $database = new Database();
        $this->db = $database->getConnection();
    }

    //Tela de login
    public function login() {

        $this->view('auth/login', ['titulo' => 'Login']);
    }

    //Processa login
    public function autenticar() {


        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $usuarioModel = new Usuario($this->db);

        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario) {

            if (password_verify($senha, $usuario['senha'])) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                header("Location: " . BASE_URL . "/dashboard");
                exit;

            } else {
                $_SESSION['erro'] = "Senha incorreta";
            }

        } else {
            $_SESSION['erro'] = "Usuário não encontrado";
        }

        header("Location: " . BASE_URL . "/login");
        exit;
    }

    //Logout
    public function logout() {
        
        session_destroy();
        header("Location: " . BASE_URL . "/");
    }

    //Mostra a tela de cadastro
    public function register() {

        // Renderiza view dentro do layout
        $this->view('auth/register',[
            'titulo' => 'Cadastro'
        ]);
    }

    public function salvar() {
        
        //Só aceita resquisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/");
            exit;
        }

        //Instancia usuário
        $usuario = new Usuario($this->db);

        //Recebe dados com segurança
        $usuario->nome  = trim($_POST['nome'] ?? '');
        $usuario->email = trim($_POST['email'] ?? '');
        $usuario->senha = $_POST['senha'] ?? '';

        //VALIDAÇÃO - Campos obrigatórios
        if (empty($usuario->nome) || empty($usuario->email) || empty($usuario->senha)) {
            $_SESSION['erro'] = "Preencha todos os campos";
            header("Location: ". BASE_URL . "/register");
            exit;
        }

        //VALIDAÇÃO - email válido
        if (!filter_var($usuario->email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = "E-mail inválido";
            header("Location: ". BASE_URL . "/register");
            exit;
        }

        // VERIFICA SE EMIAL JÁ EXISTE
        $stmt = $usuario->buscarPorEmail($usuario->email);
        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "E-mail já cadastrado";
            header("Location:" . BASE_URL . "/register");
            exit;
        }

        //SALVA NO BANCO
        if ($usuario->criar()) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso";
            header("Location: " . BASE_URL . "/");
            exit;

        } else {
            $_SESSION['erro'] = "Erro ao cadastrar";
            header("Location: " . BASE_URL . "/register");
            exit;
        }

    }

    //Tela de convite
    public function convite() {

        $token = $_GET['token'] ?? null;

        if (!$token) {
            echo "Token inválido";
            exit;
        }

        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->buscarPorToken($token);

        if (!$usuario) {
            echo "Convite inválido ou expirado";
            exit;
        }

        $this->view('auth/convite', [
            'usuario' => $usuario
        ]);
    }

    // Aceitar Convite
    public function aceitarConvite() {

        $token = $_POST['token'];
        $senha = $_POST['senha'];

        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->buscarPorToken($token);

        if (!$usuario) {
            $_SESSION['erro'] = "Token inválido";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        // Atualiza senha e ativa conta
        $usuarioModel->id = $usuario['id'];
        $usuarioModel->senha = password_hash($senha, PASSWORD_DEFAULT);

        $usuarioModel->ativarConta();

        $_SESSION['sucesso'] = "Conta ativada! Faça login.";
        header("Location: " . BASE_URL . "/login");
        exit;
    }

}

?>