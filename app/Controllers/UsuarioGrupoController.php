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
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        \Core\Permissao::admin($grupo_id);

        // Paginação
        $paginaAtual = (int)($_GET['page'] ?? 1);
        $porPagina   = (int)($_GET['per_page'] ?? 10);

        // Total de registros
        $total = \Models\GrupoUsuario::contarPorGrupo($grupo_id);

        // Paginator
        $paginator = new \Core\Paginator($total, $paginaAtual, $porPagina);

        // Lista paginada
        $usuarios = \Models\GrupoUsuario::listarPorGrupoPaginado(
            $grupo_id,
            $paginator->porPagina,
            $paginator->offset
        );

        $this->view('usuarios_grupo/index', [
            'usuarios'  => $usuarios,
            'grupo_id'  => $grupo_id,
            'paginator' => $paginator
        ]);
    }

    public function editar()
    {
        \Core\Autenticacao::verificar();

        $id = $_GET['id'] ?? null;
        if (!$id) die('ID não informado.');

        $registro = \Models\GrupoUsuario::buscarPorId($id);
        if (!$registro) die('Registro não encontrado.');

        // 🔒 Permissão: admin do grupo
        \Core\Permissao::admin($registro['grupo_id']);

        $this->view('usuarios_grupo/editar', [
            'registro' => $registro
        ]);
    }

    public function atualizar()
    {
        \Core\Autenticacao::verificar();

        $id               = $_POST['id'];
        $quantidade_cotas = (int) $_POST['quantidade_cotas'];
        $nivel            = $_POST['nivel'];
        $ativo            = isset($_POST['ativo']) ? 1 : 0;

        $registro = \Models\GrupoUsuario::buscarPorId($id);
        if (!$registro) die('Registro não encontrado.');

        \Core\Permissao::admin($registro['grupo_id']);

        // ❌ Não permitir que o admin se desative
        if ($registro['usuario_id'] == \Core\Sessao::get('usuario_id') && (!$ativo || $nivel !== 'ADMIN')) {
            die('Você não pode remover seu próprio acesso.');
        }

        \Models\GrupoUsuario::atualizar($id, $quantidade_cotas, $nivel, $ativo);

        \Services\LogService::registrar(
            \Core\Sessao::get('usuario_id'),
            'USUARIO_GRUPO',
            "Atualizou usuário {$registro['usuario_id']} no grupo {$registro['grupo_id']}"
        );

        header('Location: ' . base_url("?rota=usuarioGrupo@index&grupo_id={$registro['grupo_id']}"));
        exit;
    }

}