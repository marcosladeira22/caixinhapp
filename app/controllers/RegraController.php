<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/RegraEmprestimo.php';
require_once __DIR__ . '/../core/Controller.php';

class RegraController extends Controller {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // LISTAR / EDITAR REGRA
    public function index() {

        $grupo_id = $_GET['grupo_id'] ?? null;

        if (!$grupo_id) {
            echo "Grupo não informado";
            exit;
        }

        $model = new RegraEmprestimo($this->db);
        $regra = $model->buscarPorGrupo($grupo_id);

        $this->view('regras/index', [
            'regra' => $regra,
            'grupo_id' => $grupo_id
        ]);
    }

    // FORMULÁRIO
    public function create() {

        $grupo_id = $_GET['grupo_id'] ?? null;

        $this->view('regras/create', [
            'grupo_id' => $grupo_id
        ]);
    }

    // SALVAR
    public function store() {

        $model = new RegraEmprestimo($this->db);

        $model->grupo_id            = $_POST['grupo_id'];
        $model->valor_minimo        = $_POST['valor_minimo'];
        $model->valor_maximo        = $_POST['valor_maximo'];
        $model->juros_inicial_tipo  = $_POST['juros_inicial_tipo'];
        $model->juros_inicial_valor = $_POST['juros_inicial_valor'];
        $model->juros_atraso_tipo   = $_POST['juros_atraso_tipo'];
        $model->juros_atraso_valor  = $_POST['juros_atraso_valor'];
        $model->dias_tolerancia     = $_POST['dias_tolerancia'];

        // verifica se já existe
        $existe = $model->buscarPorGrupo($model->grupo_id);

        if ($existe) {
            $model->atualizar();
        } else {
            $model->criar();
        }

        header("Location: " . BASE_URL . "/regras?grupo_id=" . $model->grupo_id);
        exit;
    }
}