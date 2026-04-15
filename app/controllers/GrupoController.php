<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Grupo.php';
require_once __DIR__ . '/../models/GrupoUsuario.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cota.php';
require_once __DIR__ . '/../models/Pagamento.php';
require_once __DIR__ . '/../models/Emprestimo.php';
require_once __DIR__ . '/../models/RegraEmprestimo.php';

class GrupoController extends Controller {

    private $db;

    public function __construct() {
        // Instancia conexão com banco
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // =========================
    // DASHBOARD (LISTA GRUPOS)
    // =========================
    public function index() {

        $grupoModel = new Grupo($this->db);

        // Lista grupos do usuário logado
        $grupos = $grupoModel->listarPorUsuario($_SESSION['usuario_id']);

        $this->view('dashboard/index', [
            'titulo' => 'Dashboard',
            'grupos' => $grupos
        ]);
    }

    // =========================
    // FORM CRIAR GRUPO
    // =========================
    public function create() {

        $this->view('grupo/create', [
            'titulo' => 'Criar Grupo'
        ]);
    }

    // =========================
    // SALVAR GRUPO
    // =========================
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }

        $grupo = new Grupo($this->db);

        $grupo->nome = $_POST['nome'];
        $grupo->descricao = $_POST['descricao'];
        $grupo->valor_cota = $_POST['valor_cota'];
        $grupo->criado_por = $_SESSION['usuario_id'];

        if ($grupo->criar()) {

            // Adiciona criador como ADMIN no grupo
            $grupoUsuario = new GrupoUsuario($this->db);
            $grupoUsuario->grupo_id = $this->db->lastInsertId();
            $grupoUsuario->usuario_id = $_SESSION['usuario_id'];
            $grupoUsuario->nivel = 'admin';
            $grupoUsuario->salvar();

            $_SESSION['sucesso'] = "Grupo criado com sucesso";

        } else {
            $_SESSION['erro'] = "Erro ao criar grupo";
        }

        header("Location: " . BASE_URL . "/dashboard");
        exit;
    }

    // =========================
    // VISUALIZAR GRUPO
    // =========================
    public function show($id) {

        require_once __DIR__ . '/../models/Emprestimo.php';

        $emprestimoModel = new Emprestimo($this->db);
        $emprestimoModel->atualizarStatusEJuros($id);

        $grupoModel = new Grupo($this->db);
        $grupoUsuario = new GrupoUsuario($this->db);

        // Verifica acesso
        if (!$grupoUsuario->usuarioPertence($id, $_SESSION['usuario_id'])) {
            $_SESSION['erro'] = "Sem acesso ao grupo";
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }

        // Dados do grupo
        $grupo = $grupoModel->buscarPorId($id);

        if (!$grupo) {
            $_SESSION['erro'] = "Grupo não encontrado";
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }

        // Membros
        $membros = $grupoUsuario->listarMembros($id);

        // Cotas
        $cotaModel = new Cota($this->db);
        $cotas = $cotaModel->listarPorGrupo($id);

        $cotasMap = [];
        foreach ($cotas as $c) {
            $cotasMap[$c['usuario_id']] = $c['quantidade'];
        }

        // Pagamentos
        $mesAtual = $_GET['mes'] ?? date('Y-m-01');

        $pagamentoModel = new Pagamento($this->db);
        $pagamentos = $pagamentoModel->listarPorMes($id, $mesAtual);

        $pagamentosMap = [];
        foreach ($pagamentos as $p) {
            $pagamentosMap[$p['usuario_id']] = [
                'status' => $p['status'],
                'valor_pago' => $p['valor_pago']
            ];
        }

        // DASHBOARD FINANCEIRO
        $totalEsperado = 0;
        $totalArrecadado = 0;

        foreach ($cotasMap as $usuario_id => $qtd) {

            $valor = $qtd * $grupo['valor_cota'];

            $totalEsperado += $valor;

            if (isset($pagamentosMap[$usuario_id]) &&
                $pagamentosMap[$usuario_id]['status'] === 'pago') {

                $totalArrecadado += $pagamentosMap[$usuario_id]['valor_pago'];
            }
        }

        $totalPendente = $totalEsperado - $totalArrecadado;

        $percentualPago = $totalEsperado > 0
            ? ($totalArrecadado / $totalEsperado) * 100
            : 0;

        $emprestimoModel = new Emprestimo($this->db);
        $emprestimos = $emprestimoModel->listarPorGrupo($id);

        $totalEmprestado = 0;
        $totalRecebidoEmprestimos = 0;

        foreach ($emprestimos as $e) {

            // Total emprestado (dinheiro saiu)
            if ($e['status'] === 'aberto' || $e['status'] === 'atrasado') {
                $totalEmprestado += $e['valor'];
            }

            // Total já pago (dinheiro voltou)
            if ($e['status'] === 'pago') {
                $totalRecebidoEmprestimos += $e['valor_com_juros'];
            }
        }

        // SALDO REAL DO CAIXA
        $saldoReal = $totalArrecadado 
                    + $totalRecebidoEmprestimos 
                    - $totalEmprestado;

        $regras = null;

        require_once __DIR__ . '/../models/Emprestimo.php';

        $emprestimoModel = new Emprestimo($this->db);

        $totalEmprestado = $emprestimoModel->totalEmprestado($id);
        $totalRecebidoEmprestimos = $emprestimoModel->totalRecebido($id);

        // saldo real do grupo
        $saldoReal = $totalArrecadado - $totalEmprestado + $totalRecebidoEmprestimos;

        $this->view('grupo/show', [
            'titulo'                   => $grupo['nome'],
            'grupo'                    => $grupo,
            'membros'                  => $membros,
            'cotasMap'                 => $cotasMap,
            'pagamentosMap'            => $pagamentosMap,
            'mesAtual'                 => $mesAtual,
            'totalArrecadado'          => $totalArrecadado,
            'totalPendente'            => $totalPendente,
            'percentualPago'           => $percentualPago,
            'totalEmprestado'          => $totalEmprestado,
            'totalRecebidoEmprestimos' => $totalRecebidoEmprestimos,
            'saldoReal'                => $saldoReal,
            'regras'                   => $regras,
            'totalEmprestado'          => $totalEmprestado,
            'totalRecebidoEmprestimos' => $totalRecebidoEmprestimos,
            'saldoReal'                => $saldoReal
        ]);
    }

    // =========================
    // ADICIONAR MEMBRO
    // =========================
    public function adicionarMembro() {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $grupo_id = $_POST['grupo_id'];

        $usuarioModel = new Usuario($this->db);

        // Verifica se já existe
        $usuario = $usuarioModel->buscarPorEmail($email);

        if (!$usuario) {

            // Cria novo usuário
            $usuarioModel->nome = $nome;
            $usuarioModel->email = $email;
            $usuarioModel->senha = rand(100000, 999999);
            $usuarioModel->nivel = 'membro';
            $usuarioModel->convite_token = bin2hex(random_bytes(16));
            $usuarioModel->convite_status = 'pendente';

            $usuarioModel->criar();

            // Busca novamente
            $usuario = $usuarioModel->buscarPorEmail($email);
        }

        // Vincula ao grupo
        $grupoUsuario = new GrupoUsuario($this->db);
        $grupoUsuario->grupo_id = $grupo_id;
        $grupoUsuario->usuario_id = $usuario['id'];
        $grupoUsuario->nivel = 'membro';
        $grupoUsuario->salvar();

        // Cria cota padrão
        $grupoModel = new Grupo($this->db);
        $grupo = $grupoModel->buscarPorId($grupo_id);

        $cota = new Cota($this->db);
        $cota->grupo_id = $grupo_id;
        $cota->usuario_id = $usuario['id'];
        $cota->quantidade = 1;
        $cota->valor_unitario = $grupo['valor_cota'];
        $cota->salvar();

        $_SESSION['sucesso'] = "Membro adicionado";

        header("Location: " . BASE_URL . "/grupos/" . $grupo_id);
        exit;
    }

    // =========================
    // SALVAR COTAS
    // =========================
    public function salvarCotas() {

        $grupo_id = $_POST['grupo_id'];
        $cotas = $_POST['cotas'];

        $cotaModel = new Cota($this->db);

        foreach ($cotas as $usuario_id => $quantidade) {

            $cotaModel->salvar($grupo_id, $usuario_id, $quantidade);
        }

        $_SESSION['sucesso'] = "Cotas atualizadas";

        header("Location: " . BASE_URL . "/grupos/" . $grupo_id);
        exit;
    }

    // =========================
    // SALVAR PAGAMENTOS
    // =========================
    public function salvarPagamentos() {

        $grupo_id = $_POST['grupo_id'];
        $mes = $_POST['mes'];
        $pagamentos = $_POST['pagamentos'];

        $pagamentoModel = new Pagamento($this->db);

        foreach ($pagamentos as $usuario_id => $status) {

            $pagamentoModel->salvar($grupo_id, $usuario_id, $mes, $status);
        }

        $_SESSION['sucesso'] = "Pagamentos atualizados";

        header("Location: " . BASE_URL . "/grupos/" . $grupo_id . "?mes=" . $mes);
        exit;
    }

    // =========================
    // REENVIAR CONVITE
    // =========================
    public function reenviarConvite() {
        
        $usuario_id = $_GET['usuario_id'] ?? null;

        if (!$usuario_id) {
            $_SESSION['erro'] = "Usuário inválido";
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }

        $usuarioModel = new Usuario($this->db);

        // Gera novo token
        $token = bin2hex(random_bytes(16));

        // Atualiza usuário
        $query = "UPDATE usuarios 
                SET convite_token = :token,
                    convite_status = 'pendente'
                WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":id", $usuario_id);
        $stmt->execute();

        // (Futuro: envio de e-mail aqui)

        $_SESSION['sucesso'] = "Convite reenviado com sucesso";

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // =========================
    // SOLICTAR EMPRESTIMO
    // =========================
    public function solicitarEmprestimo() {

        $grupo_id = $_POST['grupo_id'];
        $valor = $_POST['valor'];

        // Busca regra
        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($grupo_id);

        if (!$regra) {
            $_SESSION['erro'] = "Grupo sem regra de empréstimo";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Validação de limites
        if ($valor < $regra['valor_minimo'] || $valor > $regra['valor_maximo']) {
            $_SESSION['erro'] = "Valor fora do permitido";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $emprestimoModel = new Emprestimo($this->db);

        // Calcula juros
        $juros = $emprestimoModel->calcularJurosInicial($valor, $regra);

        $valorTotal = $valor + $juros;

        // Define datas
        $dataEmprestimo = date('Y-m-d');
        $dataVencimento = date('Y-m-d', strtotime('+30 days'));

        // INSERT
        $query = "INSERT INTO emprestimos 
            (grupo_id, usuario_id, valor, valor_com_juros, juros_inicial, data_emprestimo, data_vencimento, status)
            VALUES
            (:grupo_id, :usuario_id, :valor, :valor_total, :juros, :data_emp, :data_venc, 'aberto')";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->bindParam(":usuario_id", $_SESSION['usuario_id']);
        $stmt->bindParam(":valor", $valor);
        $stmt->bindParam(":valor_total", $valorTotal);
        $stmt->bindParam(":juros", $juros);
        $stmt->bindParam(":data_emp", $dataEmprestimo);
        $stmt->bindParam(":data_venc", $dataVencimento);

        $stmt->execute();

        $_SESSION['sucesso'] = "Empréstimo solicitado com juros aplicado";

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // =========================
    // REGRAS - EMPRESTIMO
    // =========================
    public function regras() {

        $grupo_id = $_GET['grupo_id'];

        $regraModel = new RegraEmprestimo($this->db);
        $regra = $regraModel->buscarPorGrupo($grupo_id);

        $this->view('grupo/regras', [
            'grupo_id' => $grupo_id,
            'regra' => $regra
        ]);
    }

    // ===========================
    // SALVAR REGRAS - EMPRESTIMO
    // ===========================
    public function salvarRegras() {

        $regraModel = new RegraEmprestimo($this->db);

        $regraModel->grupo_id            = $_POST['grupo_id'];
        $regraModel->valor_minimo        = $_POST['valor_minimo'];
        $regraModel->valor_maximo        = $_POST['valor_maximo'];
        $regraModel->juros_inicial_tipo  = $_POST['juros_inicial_tipo'];
        $regraModel->juros_inicial_valor = $_POST['juros_inicial_valor'];
        $regraModel->juros_atraso_tipo   = $_POST['juros_atraso_tipo'];
        $regraModel->juros_atraso_valor  = $_POST['juros_atraso_valor'];
        $regraModel->dias_tolerancia     = $_POST['dias_tolerancia'];

        // Verifica se já existe
        $existe = $regraModel->buscarPorGrupo($_POST['grupo_id']);

        if ($existe) {
            $regraModel->atualizar();
        } else {
            $regraModel->criar();
        }

        $_SESSION['sucesso'] = "Regras salvas com sucesso";

        header("Location: " . BASE_URL . "/grupos/" . $_POST['grupo_id']);
        exit;
    }
}