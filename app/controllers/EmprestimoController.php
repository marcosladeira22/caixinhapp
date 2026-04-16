<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emprestimo.php';
require_once __DIR__ . '/../models/Grupo.php';
require_once __DIR__ . '/../models/RegraEmprestimo.php';
require_once __DIR__ . '/../core/Controller.php';

class EmprestimoController extends Controller {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // =========================
    // LISTAGEM
    // =========================
    public function index() {

        $grupo_id = $_GET['grupo_id'];

        $model = new Emprestimo($this->db);

        // 🔥 Atualiza juros e atraso antes de listar
        $model->aplicarJurosAtrasoAutomatico($grupo_id);

        $emprestimos = $model->listarPorGrupo($grupo_id);

        $this->view('emprestimos/index', [
            'emprestimos' => $emprestimos,
            'grupo_id' => $grupo_id
        ]);
    }

    // =========================
    // FORMULÁRIO
    // =========================
    public function create() {

        $grupo_id = $_GET['grupo_id'] ?? null;

        $grupoModel = new Grupo($this->db);
        $membros = $grupoModel->buscarMembros($grupo_id);

        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($grupo_id);

        $this->view('emprestimos/create', [
            'grupo_id' => $grupo_id,
            'membros'  => $membros,
            'regra'    => $regra
        ]);
    }

    // =========================
    // SALVAR
    // =========================
    public function store() {

        // 🔒 Verifica dívida aberta
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

        // 🔒 Validação regras
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

        // 💰 Juros inicial (USANDO MODEL)
        $model = new Emprestimo($this->db);
        $juros = $regra ? $model->calcularJurosInicial($valor, $regra) : 0;

        // 📌 Dados
        $model->grupo_id = $_POST['grupo_id'];
        $model->usuario_id = $_POST['usuario_id'];
        $model->valor = $valor;
        $model->data_emprestimo = $_POST['data_emprestimo'];
        $model->data_vencimento = $_POST['data_vencimento'];
        $model->juros_inicial = $juros;
        $model->valor_com_juros = $valor + $juros;

        // 📅 Verifica se já nasceu atrasado
        $hoje = date('Y-m-d');

        if ($model->data_vencimento < $hoje) {
            $model->status = 'atrasado'; // já começa atrasado
        } else {
            $model->status = 'aberto';
        }

        $model->criar();

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $model->grupo_id);
        exit;
    }

    // =========================
    // EDITAR
    // =========================
    public function edit() {

        $id = $_GET['id'];
        $grupo_id = $_GET['grupo_id'];

        $model = new Emprestimo($this->db);
        $emprestimo = $model->buscarPorId($id);

        $this->view('emprestimos/edit', [
            'emprestimo' => $emprestimo,
            'grupo_id' => $grupo_id
        ]);
    }

    // =========================
    // ATUALIZAR
    // =========================
    public function update() {

        $model = new Emprestimo($this->db);

        $emprestimo = $model->buscarPorId($_POST['id']);

        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($emprestimo['grupo_id']);

        $valor = $_POST['valor'];

        $juros = $regra ? $model->calcularJurosInicial($valor, $regra) : 0;

        $query = "UPDATE emprestimos 
                  SET valor = :valor,
                      valor_com_juros = :total,
                      juros_inicial = :juros
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":valor", $valor);
        $stmt->bindParam(":total", $valor + $juros);
        $stmt->bindParam(":juros", $juros);
        $stmt->bindParam(":id", $_POST['id']);
        $stmt->execute();

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $emprestimo['grupo_id']);
        exit;
    }

    // =========================
    // EXCLUIR
    // =========================
    public function delete() {

        $id = $_GET['id'];
        $grupo_id = $_GET['grupo_id'];

        $model = new Emprestimo($this->db);
        $model->deletar($id);

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $grupo_id);
        exit;
    }

    // =========================
    // PAGAR
    // =========================
    public function pagar() {

        $id = $_GET['id'];
        $grupo_id = $_GET['grupo_id'];

        $model = new Emprestimo($this->db);
        $model->marcarComoPago($id);

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $grupo_id);
        exit;
    }
}