<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Models\Usuario;
use Services\LogService;

class UsuarioController extends Controller
{
    /**
     * Exibe o perfil do usuário logado
     */
    public function perfil()
    {
        Autenticacao::verificar();

        $usuario_id = Sessao::get('usuario_id');

        $usuario = Usuario::buscarPorId($usuario_id);
        if (!$usuario) {
            die('Usuário não encontrado.');
        }

        $this->view('usuario/perfil', [
            'usuario' => $usuario
        ]);
    }

    /**
     * Atualiza dados do perfil
     */
    public function atualizar()
    {
        Autenticacao::verificar();

        $usuario_id = Sessao::get('usuario_id');

        $nome     = trim($_POST['nome']);
        $telefone = trim($_POST['telefone']);
        $sexo     = $_POST['sexo'];
        $senha    = $_POST['senha'] ?? null;

        if (!$nome || !$sexo) {
            die('Dados inválidos.');
        }

        Usuario::atualizarDadosBasicos(
            $usuario_id,
            $nome,
            $telefone ?: null,
            $sexo
        );

        // Atualiza senha apenas se foi informada
        if (!empty($senha)) {
            Usuario::atualizarSenha($usuario_id, $senha);
        }

        LogService::registrar(
            $usuario_id,
            'PERFIL',
            'Usuário atualizou seus dados de perfil'
        );

        header('Location: ' . base_url('?rota=usuario@perfil'));
        exit;
    }
}