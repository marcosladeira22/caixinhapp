<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emprestimo.php';
require_once __DIR__ . '/../core/Controller.php';

class EmprestimoController extends Controller {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {

        $grupo_id = $_GET['grupo_id'];

        $model = new Emprestimo($this->db);
        $emprestimos = $model->listarPorGrupo($grupo_id);

        $this->view('emprestimos/index', [
            'emprestimos' => $emprestimos,
            'grupo_id' => $grupo_id
        ]);
    }

    // FORMULÁRIO
    public function create() {

        $grupo_id = $_GET['grupo_id'] ?? null;

        // Buscar membros do grupo
        require_once __DIR__ . '/../models/Grupo.php';
        $grupoModel = new Grupo($this->db);
        $membros = $grupoModel->buscarMembros($grupo_id);

        $this->view('emprestimos/create', [
            'grupo_id' => $grupo_id,
            'membros' => $membros
        ]);
    }

    // SALVAR EMPRÉSTIMO
    public function store() {

        $model = new Emprestimo($this->db);

        $model->grupo_id = $_POST['grupo_id'];
        $model->usuario_id = $_POST['usuario_id'];
        $model->valor = $_POST['valor'];
        $model->data_emprestimo = $_POST['data_emprestimo'];
        $model->data_vencimento = $_POST['data_vencimento'];

        // 💡 TEMPORÁRIO (na fase 7.3 vamos calcular automático)
        $model->juros_inicial = 0;
        $model->valor_com_juros = $model->valor;

        $model->criar();

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $model->grupo_id);
        exit;
    }
}