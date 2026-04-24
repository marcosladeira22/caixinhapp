<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Core\Permissao;
use Models\Pagamento;
use Models\GrupoUsuario;
use Models\Grupo;
use Services\ScoreService;


class PagamentoController extends Controller
{

    /**
     * 
     */
    public function index()
    {
        \Core\Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) die('Grupo não informado');

        \Core\Permissao::admin($grupo_id);

        $mesAtual = date('Y-m-01');

        $pagamentos = \Models\Pagamento::listarPorGrupoMes($grupo_id, $mesAtual);

        $this->view('pagamentos/index', [
            'grupo_id'   => $grupo_id,
            'mes'        => $mesAtual,
            'pagamentos' => $pagamentos
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

    
}