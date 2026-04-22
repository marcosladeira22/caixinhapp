<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\EmprestimoService;
use Exception;

class EmprestimoController extends Controller
{
    /**
     * Solicitação de empréstimo (membro ou admin)
     */
    public function solicitar()
    {
        Autenticacao::verificar();

        $grupo_id   = $_GET['grupo_id'] ?? null;
        $usuario_id = Sessao::get('usuario_id');

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $valor = (float) $_POST['valor'];

            try {
                EmprestimoService::solicitar($usuario_id, $grupo_id, $valor);

                header('Location: ' . base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}"));
                exit;

            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }

        $this->view('emprestimos/solicitar', compact('grupo_id', 'erro'));
    }
}