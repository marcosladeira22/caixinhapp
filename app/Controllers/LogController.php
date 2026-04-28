<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Models\Log;

/**
 * Controller responsável pela visualização dos logs
 */
class LogController extends Controller
{
    public function index()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // 🔒 Apenas ADMIN pode ver logs
        Permissao::admin($grupo_id);

        // Filtro opcional de ação
        $acao = $_GET['acao'] ?? null;

        $logs = Log::listarPorGrupo($grupo_id, $acao);

        $this->view('logs/index', [
            'logs' => $logs,
            'grupo_id' => $grupo_id,
            'acao' => $acao
        ]);
    }
}