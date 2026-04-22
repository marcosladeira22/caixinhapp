<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Models\Usuario;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'];

            if (!$email || !$senha) {
                $this->view('auth/login', ['erro' => 'Dados inválidos']);
                return;
            }

            $usuario = Usuario::buscarPorEmail($email);

            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                $this->view('auth/login', ['erro' => 'E-mail ou senha incorretos']);
                return;
            }

            Sessao::set('usuario_id', $usuario['id']);
            Sessao::set('usuario_nome', $usuario['nome']);

            header('Location: ' . base_url('?rota=dashboard@index'));
            exit;
        }

        $this->view('auth/login');
    }

    /**
    * Cadastro de novo usuário (futuro ADMIN)
    */
    public function cadastro()
    {
        // Se enviou o formulário
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome     = trim($_POST['nome']);
            $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha    = $_POST['senha'];
            $telefone = trim($_POST['telefone']);
            $sexo     = $_POST['sexo'] ?? 'O';


            if (!$nome || !$email || !$senha) {
                $erro = 'Preencha todos os campos.';
                $this->view('auth/cadastro', compact('erro'));
                return;
            }

            // Verifica se e-mail já existe
            if (Usuario::buscarPorEmail($email)) {
                $erro = 'E-mail já cadastrado.';
                $this->view('auth/cadastro', compact('erro'));
                return;
            }

            // Cria usuário
            $usuario_id = Usuario::criar([
                ':nome'     => $nome,
                ':email'    => $email,
                ':senha'    => password_hash($senha, PASSWORD_DEFAULT),
                ':telefone' => $telefone ?: null,
                ':sexo'     => $sexo
            ]);

            // Login automático após cadastro
            Sessao::set('usuario_id', $usuario_id);
            Sessao::set('usuario_nome', $nome);

            // Redireciona para criação do primeiro grupo
            header('Location: ' . base_url('?rota=grupo@criar'));
            exit;
        }

        // Mostra formulário
        $this->view('auth/cadastro');
    }
}