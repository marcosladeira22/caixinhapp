<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Models\Grupo;
use Models\GrupoUsuario;

class GrupoController extends Controller
{
    // Lista grupos do usuário
    public function index()
    {
        $usuario_id = Sessao::get('usuario_id');

        $grupos = Grupo::listarPorUsuario($usuario_id);

        $this->view('grupos/index', [
            'grupos' => $grupos
        ]);
    }

    // Criação de grupo (admin vira admin automaticamente)
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = [
                'nome'            => $_POST['nome'],
                'valor_cota'      => $_POST['valor_cota'],
                'emprestimo_min'  => $_POST['emprestimo_min'],
                'emprestimo_max'  => $_POST['emprestimo_max'],
                'taxa_tipo'       => $_POST['taxa_tipo'],
                'taxa_valor'      => $_POST['taxa_valor'],
                'juros_tipo'      => $_POST['juros_tipo'],
                'juros_valor'     => $_POST['juros_valor'],
                'dias_tolerancia' => $_POST['dias_tolerancia']
            ];

            $grupo_id = Grupo::criar($dados);

            // Usuário criador vira ADMIN do grupo
            GrupoUsuario::adicionarUsuarioAoGrupo(
                Sessao::get('usuario_id'),
                $grupo_id,
                'ADMIN',
                1
            );

            header('Location: ' . base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}"));
            exit;
        }

        $this->view('grupos/criar');
    }
}