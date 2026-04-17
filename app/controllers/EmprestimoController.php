<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emprestimo.php';
require_once __DIR__ . '/../models/Grupo.php';
require_once __DIR__ . '/../models/GrupoUsuario.php';
require_once __DIR__ . '/../models/RegraEmprestimo.php';
require_once __DIR__ . '/../core/Controller.php';

class EmprestimoController extends Controller {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // =========================
    // 🔐 MIDDLEWARE (ADMIN)
    // =========================
    private function onlyAdmin($grupo_id) {

        $grupoUsuario = new GrupoUsuario($this->db);

        $nivel = $grupoUsuario->buscarNivel($_SESSION['usuario_id'], $grupo_id);

        // master SEMPRE pode
        if ($_SESSION['nivel'] === 'master') {
            return true;
        }

        if ($nivel !== 'admin') {
            $_SESSION['erro'] = "Acesso restrito ao administrador";
            header("Location: " . BASE_URL . "/emprestimos?grupo_id=" . $grupo_id);
            exit;
        }
    }

    // =========================
    // LISTAGEM
    // =========================
    public function index() {

        $grupoUsuarioModel = new GrupoUsuario($this->db);
        $grupo_id          = $_GET['grupo_id'];
        $nivelGrupo        = $grupoUsuarioModel->buscarNivel(
            $_SESSION['usuario_id'],$grupo_id
            );

        $model = new Emprestimo($this->db);

        $model->aplicarJurosAtrasoAutomatico($grupo_id);

        $emprestimos = $model->listarPorGrupo($grupo_id);

        $this->view('emprestimos/index', [
            'emprestimos' => $emprestimos,
            'grupo_id' => $grupo_id,
            'nivel_grupo' => $nivelGrupo //
]);
    }

    // =========================
    // SALVAR (FLUXO COMPLETO)
    // =========================
    public function store() {

        $grupo_id = $_POST['grupo_id'];
        $usuario_id = $_POST['usuario_id'];
        $valor = $_POST['valor'];

        // =========================
        // 🔒 VALIDAÇÃO DÍVIDA
        // =========================
        $query = "SELECT COUNT(*) as total 
                  FROM emprestimos 
                  WHERE usuario_id = :usuario_id 
                  AND status IN ('aberto','atrasado')";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0) {
            $_SESSION['erro'] = "Usuário já possui empréstimo em aberto";
            header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=$grupo_id");
            exit;
        }

        // =========================
        // 📌 REGRA
        // =========================
        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($grupo_id);

        if (!$regra) {
            $_SESSION['erro'] = "Grupo sem regras definidas";
            header("Location: " . BASE_URL . "/emprestimos?grupo_id=$grupo_id");
            exit;
        }

        // =========================
        // 🧠 SCORE
        // =========================
        $emprestimoModel = new Emprestimo($this->db);

        $historico = $emprestimoModel->listarPorUsuarioGrupo($usuario_id, $grupo_id);

        $total = count($historico);
        $atrasados = 0;

        foreach ($historico as $e) {
            if ($e['status'] === 'atrasado') $atrasados++;
        }

        $score = $total > 0 ? 100 - (($atrasados / $total) * 100) : 100;

        if ($score < 50) {
            $_SESSION['erro'] = "Usuário com alto risco";
            header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=$grupo_id");
            exit;
        }

        // =========================
        // 📊 LIMITE
        // =========================
        $multiplicador = ($score >= 80) ? 1 : 0.5;
        $valorMax = $regra['valor_maximo'] * $multiplicador;

        if ($valor < $regra['valor_minimo'] || $valor > $valorMax) {
            $_SESSION['erro'] = "Valor fora do limite";
            header("Location: " . BASE_URL . "/emprestimos/create?grupo_id=$grupo_id");
            exit;
        }

        // =========================
        // 👤 PERFIL
        // =========================
        $grupoUsuario = new GrupoUsuario($this->db);
        $nivel = $grupoUsuario->buscarNivel($_SESSION['usuario_id'], $grupo_id);

        $isAdmin = ($nivel === 'admin');
        $isMaster = ($_SESSION['nivel'] === 'master');

        // =========================
        // 💰 JUROS
        // =========================
        $juros = $emprestimoModel->calcularJurosInicial($valor, $regra);

        // =========================
        // 🔥 STATUS
        // =========================
        $status = ($isAdmin || $isMaster) ? 'aberto' : 'pendente';

        // =========================
        // 💾 SALVAR
        // =========================
        $emprestimoModel->grupo_id = $grupo_id;
        $emprestimoModel->usuario_id = $usuario_id;
        $emprestimoModel->valor = $valor;
        $emprestimoModel->juros_inicial = $juros;
        $emprestimoModel->valor_com_juros = $valor + $juros;
        $emprestimoModel->data_emprestimo = date('Y-m-d');
        $emprestimoModel->data_vencimento = date('Y-m-d', strtotime('+30 days'));
        $emprestimoModel->status = $status;

        $emprestimoModel->criar();

        $_SESSION['sucesso'] = ($status === 'pendente')
            ? "Solicitação enviada para aprovação"
            : "Empréstimo criado com sucesso";

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=$grupo_id");
        exit;
    }

    // =========================
    // ✅ APROVAR
    // =========================
    public function aprovar() {

        $id = $_GET['id'];
        $grupo_id = $_GET['grupo_id'];

        // 🔒 middleware
        $this->onlyAdmin($grupo_id);

        $query = "UPDATE emprestimos 
                  SET status = 'aberto' 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $_SESSION['sucesso'] = "Empréstimo aprovado";

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=$grupo_id");
        exit;
    }

    // =========================
    // ❌ RECUSAR
    // =========================
    public function recusar() {

        $id = $_GET['id'];
        $grupo_id = $_GET['grupo_id'];

        // 🔒 middleware
        $this->onlyAdmin($grupo_id);

        $query = "UPDATE emprestimos 
                  SET status = 'recusado' 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $_SESSION['sucesso'] = "Empréstimo recusado";

        header("Location: " . BASE_URL . "/emprestimos?grupo_id=$grupo_id");
        exit;
    }
}