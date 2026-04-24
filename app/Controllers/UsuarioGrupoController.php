<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Core\Autenticacao;
use Core\Permissao;
use Models\Usuario;
use Models\GrupoUsuario;

class UsuarioGrupoController extends Controller
{
    /**
     * Tela e processamento de cadastro de usuário no grupo
     */
    public function criar()
    {
        // 🔒 Garante que o usuário esteja logado
        Autenticacao::verificar();

        // Grupo selecionado
        $grupo_id = $_GET['grupo_id'] ?? null;

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // 🔒 Somente ADMIN pode cadastrar usuários
        Permissao::admin($grupo_id);

        // Se formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Sanitização básica dos dados
            $nome             = trim($_POST['nome']);
            $email            = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $telefone         = trim($_POST['telefone']);
            $sexo             = $_POST['sexo'];
            $nivel            = $_POST['nivel'];
            $quantidade_cotas = (int) $_POST['quantidade_cotas'];

            if (!$nome || !$email || !$nivel) {
                $erro = "Preencha os campos obrigatórios.";
                $this->view('usuarios_grupo/criar', compact('erro', 'grupo_id'));
                return;
            }

            // 🔍 Verifica se usuário já existe
            $usuario = Usuario::buscarPorEmail($email);

            if (!$usuario) {
                // Criação de senha temporária
                $senha_temporaria = password_hash('123456', PASSWORD_DEFAULT);

                // Cria usuário
                $usuario_id = Usuario::criar([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha_temporaria,
                    ':telefone' => $telefone,
                    ':sexo' => $sexo
                ]);
            } else {
                $usuario_id = $usuario['id'];
            }

            // Associa o usuário ao grupo
            GrupoUsuario::adicionarUsuarioAoGrupo(
                $usuario_id,
                $grupo_id,
                $nivel,
                $quantidade_cotas
            );

            // Redireciona para o dashboard
            header("Location: " . base_url("?rota=dashboard@index&grupo_id={$grupo_id}"));
            exit;
        }

        // Exibe formulário
        $this->view('usuarios_grupo/criar', [
            'grupo_id' => $grupo_id
        ]);
    }

    public function index()
    {
        \Core\Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) die('Grupo não informado');

        \Core\Permissao::admin($grupo_id);

        $usuarios = \Models\GrupoUsuario::listarPorGrupo($grupo_id);

        $this->view('usuarios_grupo/index', compact('usuarios', 'grupo_id'));
    }
}