<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Core\Sessao;
use Services\PagamentoService;
use Services\PagamentoConsultaService;
use Models\GrupoUsuario;
use Models\Grupo;
use Exception;

/**
 * Controller responsável por pagamentos de cotas
 */
class PagamentoController extends Controller
{
    /**
     * Lista pagamentos do grupo (ADMIN)
     */
    public function index()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $pagina    = (int)($_GET['page'] ?? 1);
        $porPagina = (int)($_GET['per_page'] ?? 10);

        $resultado = PagamentoConsultaService::listarPorGrupoMesAtual(
            (int)$grupoId,
            $pagina,
            $porPagina
        );

        $this->view('pagamentos/index', [
            'grupo_id'   => $grupoId,
            'mes'        => $resultado['mes'],
            'pagamentos' => $resultado['pagamentos'],
            'paginator'  => $resultado['paginator']
        ]);
    }

    /**
     * Registra pagamento (ADMIN)
     */
    public function pagar()
    {
        Autenticacao::exigirLogin();

        $grupoId   = $_POST['grupo_id']   ?? null;
        $usuarioId = $_POST['usuario_id'] ?? null;

        if (!$grupoId || !$usuarioId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        // Recupera dados necessários (apenas leitura)
        $grupoUsuario = GrupoUsuario::buscarPorUsuarioEGrupo((int)$usuarioId, (int)$grupoId);
        if (!$grupoUsuario) {
            $this->redirect('?rota=dashboard@index');
        }

        $grupo = Grupo::buscarPorId((int)$grupoId);

        // ✅ Delegação TOTAL ao Service
        PagamentoService::registrarPagamento(
            (int)$usuarioId,
            (int)$grupoId,
            (int)$grupoUsuario['quantidade_cotas'],
            (float)$grupo['valor_cota']
        );

        $this->redirect("?rota=pagamento@index&grupo_id={$grupoId}");
    }
}