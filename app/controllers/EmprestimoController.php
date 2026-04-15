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

        $emprestimoModel = new Emprestimo($this->db);
        $emprestimoModel->aplicarJurosAtrasoAutomatico($grupo_id);

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

        require_once __DIR__ . '/../models/RegraEmprestimo.php';

        // Verifica se usuário já tem dívida aberta
        $query = "SELECT COUNT(*) as total 
                FROM emprestimos 
                WHERE usuario_id = :usuario_id 
                AND status IN ('aberto','atrasado')";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":usuario_id", $_POST['usuario_id']);
        $stmt->execute();

        $temDivida = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($temDivida > 0) {
            $_SESSION['erro'] = "Usuário já possui empréstimo em aberto";
            header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=" . $_POST['grupo_id']);
            exit;
        }

        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($_POST['grupo_id']);

        $valor = $_POST['valor'];

        if ($regra) {

            if ($valor < $regra['valor_minimo']) {
                $_SESSION['erro'] = "Valor abaixo do mínimo permitido";
                header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=" . $_POST['grupo_id']);
                exit;
            }

            if ($valor > $regra['valor_maximo']) {
                $_SESSION['erro'] = "Valor acima do máximo permitido";
                header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=" . $_POST['grupo_id']);
                exit;
            }

        }

        $juros = 0;

        if ($regra) {

            // JUROS INICIAL
            if ($regra['juros_inicial_tipo'] === 'percentual') {
                $juros = ($valor * $regra['juros_inicial_valor']) / 100;
            } else {
                $juros = $regra['juros_inicial_valor'];
            }
        }


        $model = new Emprestimo($this->db);

        $model->grupo_id = $_POST['grupo_id'];
        $model->usuario_id = $_POST['usuario_id'];
        $model->valor = $_POST['valor'];
        $model->data_emprestimo = $_POST['data_emprestimo'];
        $model->data_vencimento = $_POST['data_vencimento'];

        $model->juros_inicial = $juros;
        $model->valor_com_juros = $valor + $juros;

        $model->criar();

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $model->grupo_id);
        exit;
    }

    public function pagar() {

        $id = $_GET['id'];

        $model = new Emprestimo($this->db);

        $model->marcarComoPago($id);

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $_GET['grupo_id']);
        exit;
}
}