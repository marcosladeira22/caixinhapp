<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Core\Autenticacao;
use Models\GrupoUsuario;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔒 Garante que o usuário esteja logado
        Autenticacao::verificar();

        // Pega o grupo atual pela URL (?grupo_id=1)
        $grupo_id = $_GET['grupo_id'] ?? null;

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // ID do usuário logado
        $usuario_id = Sessao::get('usuario_id');

        // Verifica o nível do usuário nesse grupo
        $nivel = GrupoUsuario::nivelUsuarioNoGrupo($usuario_id, $grupo_id);

        if (!$nivel) {
            die('Usuário não pertence a este grupo.');
        }

        // Decide qual dashboard carregar
        if ($nivel === 'ADMIN') {
            $this->dashboardAdmin($grupo_id);
        } else {
            $this->dashboardMembro($grupo_id);
        }
    }

    // 📊 Dashboard do Administrador
    private function dashboardAdmin($grupo_id)
    {
        /**
         * Aqui futuramente entrarão:
         * - Total em caixa
         * - Usuários do grupo
         * - Empréstimos
         * - Inadimplência
         */

        $this->view('dashboard/admin', [
            'grupo_id' => $grupo_id
        ]);
    }

    // 👤 Dashboard do Membro
    private function dashboardMembro($grupo_id)
    {
        /**
         * Aqui futuramente entrarão:
         * - Pagamentos do usuário
         * - Score
         * - Empréstimos pessoais
         */

        $this->view('dashboard/membro', [
            'grupo_id' => $grupo_id
        ]);
    }
}