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
     * Fluxo inicial após o login
     * Decide:
     * - se cria grupo
     * - se entra direto
     * - se escolhe grupo
     */
    public function index()
    {
        Autenticacao::verificar();

        $usuario_id = Sessao::get('usuario_id');

        // Busca os grupos do usuário
        $grupos = Grupo::listarPorUsuario($usuario_id);

        // Usuário não possui grupo → cria o primeiro
        if (empty($grupos)) {
            header('Location: ' . base_url('?rota=grupo@criar'));
            exit;
        }

        // Usuário possui apenas um grupo → entra direto
        if (count($grupos) === 1) {
            $grupo_id = $grupos[0]['id'];
            header('Location: ' . base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}"));
            exit;
        }

        // Usuário possui vários grupos → escolhe qual acessar
        $this->view('dashboard/selecionar_grupo', compact('grupos'));
    }

    /**
     * Dashboard de um grupo específico
     * AQUI está a principal mudança
     */
    public function grupo()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        $usuario_id = Sessao::get('usuario_id');

        // Verifica nível do usuário dentro do grupo
        $nivel = GrupoUsuario::nivelUsuarioNoGrupo($usuario_id, $grupo_id);

        if (!$nivel) {
            die('Usuário não pertence a este grupo.');
        }

        /**
         * 🔥 NOVO CONCEITO
         * Preparamos UM array de dados
         * O que muda é o CONTEÚDO, não a view
         */

        if ($nivel === 'ADMIN') {

            // Admin vê dados do GRUPO
            $dados = [
                'tipo'     => 'ADMIN',
                'grupo_id' => $grupo_id
                // futuramente:
                // total_caixa, inadimplentes, etc
            ];

        } else {

            // Membro vê apenas informações DELE
            $grupoUsuario = GrupoUsuario::buscar($usuario_id, $grupo_id);

            $dados = [
                'tipo'             => 'MEMBRO',
                'grupo_id'         => $grupo_id,
                'score'            => $grupoUsuario['score'],
                'quantidade_cotas' => $grupoUsuario['quantidade_cotas']
            ];
        }

        // ✅ UMA ÚNICA VIEW PARA OS DOIS PERFIS
        $this->view('dashboard/index', [
            'dados' => $dados
        ]);
    }
}