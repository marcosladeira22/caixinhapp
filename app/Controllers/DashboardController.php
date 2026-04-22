<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Models\Grupo;
use Models\GrupoUsuario;

class DashboardController extends Controller
{
    /**
     * Resolve o fluxo inicial após o login
     * Decide se:
     * - cria grupo
     * - entra direto
     * - escolhe grupo
     */
    public function index()
    {
        Autenticacao::verificar();

        $usuario_id = Sessao::get('usuario_id');

        // Busca os grupos do usuário
        $grupos = Grupo::listarPorUsuario($usuario_id);

        // 🟢 Usuário sem grupo
        if (empty($grupos)) {
            header('Location: ' . base_url('?rota=grupo@criar'));
            exit;
        }

        // 🟢 Usuário com apenas um grupo
        if (count($grupos) === 1) {
            $grupo_id = $grupos[0]['id'];
            header('Location: ' . base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}"));
            exit;
        }

        // 🟢 Usuário com vários grupos
        $this->view('dashboard/selecionar_grupo', compact('grupos'));
    }

    /**
     * Dashboard de um grupo específico
     * Decide se é ADMIN ou MEMBRO
     */
    public function grupo()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        $usuario_id = Sessao::get('usuario_id');

        // Verifica nível do usuário no grupo
        $nivel = GrupoUsuario::nivelUsuarioNoGrupo($usuario_id, $grupo_id);

        if (!$nivel) {
            die('Usuário não pertence a este grupo.');
        }

        // ✅ Aqui reaproveitamos os métodos privados
        if ($nivel === 'ADMIN') {
            $this->dashboardAdmin($grupo_id);
        } else {
            $this->dashboardMembro($grupo_id);
        }
    }

    /**
     * 📊 Dashboard do ADMINISTRADOR
     * Responsável apenas por montar a visão do admin
     */
    private function dashboardAdmin($grupo_id)
    {
        /**
         * Futuro:
         * - total em caixa
         * - inadimplentes
         * - empréstimos
         * - membros
         */

        $this->view('dashboard/admin', [
            'grupo_id' => $grupo_id
        ]);
    }

    /**
     * 👤 Dashboard do MEMBRO
     * Mostra somente dados pessoais
     */
    private function dashboardMembro($grupo_id)
    {
        /**
         * Futuro:
         * - pagamentos do usuário
         * - score
         * - empréstimos pessoais
         */

        $this->view('dashboard/membro', [
            'grupo_id' => $grupo_id
        ]);
    }
}