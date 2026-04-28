<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Core\Paginator;
use Core\Permissao;
use Models\Pagamento;
use Models\GrupoUsuario;
use Models\Grupo;
use Services\ScoreService;



class PagamentoController extends Controller
{

    
    /**
     * Lista pagamentos de cotas do grupo (com paginação)
     * Apenas ADMIN pode acessar
     */
    public function index()
    {
        // 🔒 Verifica se o usuário está autenticado
        \Core\Autenticacao::verificar();

        // ✅ Grupo obrigatório
        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // 🔒 Somente ADMIN do grupo
        \Core\Permissao::admin($grupo_id);

        // ✅ Mês de referência da cota (sempre o mês atual)
        $mesAtual = date('Y-m-01');

        // ✅ Parâmetros de paginação vindos da URL
        $paginaAtual = (int)($_GET['page'] ?? 1);
        $porPagina   = (int)($_GET['per_page'] ?? 10);

        // ✅ Total de registros (para calcular páginas reais)
        $totalRegistros = \Models\Pagamento::contarPorGrupoMes(
            $grupo_id,
            $mesAtual
        );

        // ✅ Cria o paginator (objeto central da paginação)
        $paginator = new \Core\Paginator(
            $totalRegistros,
            $paginaAtual,
            $porPagina
        );

        // ✅ Busca os pagamentos já paginados
        $pagamentos = \Models\Pagamento::listarPorGrupoMesPaginado(
            $grupo_id,
            $mesAtual,
            $paginator->porPagina,
            $paginator->offset
        );

        // ✅ Envia tudo para a view (NADA solto)
        $this->view('pagamentos/index', [
            'grupo_id'   => $grupo_id,
            'mes'        => $mesAtual,
            'pagamentos' => $pagamentos,
            'paginator'  => $paginator
        ]);
    }


    /**
    * Tela e ação de lançamento de pagamento
    */
    public function criar()
    {
        // 🔒 Usuário precisa estar logado
        Autenticacao::verificar();

        $grupo_id             = $_GET['grupo_id'] ?? null;
        $usuario_id_pagamento = $_GET['usuario_id'] ?? null;

        if (!$grupo_id || !$usuario_id_pagamento) {
            die('Grupo ou usuário não informado.');
        }

        // 🔒 Somente ADMIN pode lançar pagamento para terceiros
        Permissao::admin($grupo_id);

        // Busca dados do usuário no grupo
        // (quantidade de cotas influencia o valor)
        $grupoUsuario = GrupoUsuario::buscar($usuario_id_pagamento, $grupo_id);

        if (!$grupoUsuario) {
            die('Usuário não pertence ao grupo.');
        }

        // Simples: vencimento todo dia 10
        $dia_vencimento = '10';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mes_referencia = $_POST['mes_referencia'];
            $data_pagamento = $_POST['data_pagamento'];

            // Valor = valor da cota * quantidade de cotas
            $valor = $_POST['valor'];

            // Calcula atraso
            $vencimento  = date('Y-m-' . $dia_vencimento, strtotime($mes_referencia));
            $dias_atraso = Pagamento::calcularAtraso($vencimento, $data_pagamento);

            Pagamento::registrar([
                ':usuario_id'     => $usuario_id_pagamento,
                ':grupo_id'       => $grupo_id,
                ':mes_referencia' => $mes_referencia,
                ':valor'          => $valor,
                ':data_pagamento' => $data_pagamento,
                ':dias_atraso'    => $dias_atraso
            ]);

            // ✅ Atualiza score automaticamente
            ScoreService::atualizarScore(
                $usuario_id_pagamento,
                $grupo_id,
                $dias_atraso
            );


            header("Location: " . base_url("?rota=dashboard@index&grupo_id={$grupo_id}"));
            exit;
        }

        $this->view('pagamentos/criar', [
            'grupo_id'         => $grupo_id,
            'usuario_id'       => $usuario_id_pagamento,
            'quantidade_cotas' => $grupoUsuario['quantidade_cotas']
        ]);
    }

    /**
    * Tela e ação de lançamento de pagamento
    */
    public function pagar()
    {
        \Core\Autenticacao::verificar();

        // Apenas ADMIN pode registrar pagamento
        $grupo_id   = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;

        if (!$grupo_id || !$usuario_id) {
            die('Dados inválidos.');
        }

        \Core\Permissao::admin($grupo_id);

        // Busca dados do usuário no grupo

        $grupoUsuario = \Models\GrupoUsuario::buscar($usuario_id, $grupo_id);

        if (!$grupoUsuario) {
            die('Usuário não pertence ao grupo.');
        }

        // Busca valor da cota no grupo
        $grupo = \Models\Grupo::buscarPorId($grupo_id);

        // ✅ Chama a regra
        \Services\PagamentoService::registrarPagamento(
            $usuario_id,
            $grupo_id,
            $grupoUsuario['quantidade_cotas'],
            $grupo['valor_cota']
        );

        // Volta para a lista de pagamentos
        header('Location: ' . base_url("?rota=pagamento@index&grupo_id={$grupo_id}"));
        exit;
    }

    
}