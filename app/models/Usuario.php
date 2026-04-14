<?php

class Usuario {

    private $conn;
    private $table = "usuarios";

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $nivel;
    public $convite_token;
    public $convite_status;

    //Recebe conexão
    public function __construct($db) {
        $this->conn = $db;
    }

    //Busca usuário pelo e-mail
        public function buscarPorEmail($email) {
        $query = "SELECT * FROM " . $this->table . 
                " WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email", $email);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Criar usuário
    public function criar() {

        // Query de inserção
        $query = "INSERT INTO usuarios 
                (nome, email, senha, nivel, convite_token, convite_status)
                VALUES 
                (:nome, :email, :senha, :nivel, :convite_token, :convite_status)";

        // Prepara a query
        $stmt = $this->conn->prepare($query);

        // Criptografa a senha antes de salvar
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        // Bind correto (sempre 2 parâmetros)
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":nivel", $this->nivel);
        $stmt->bindParam(":convite_token", $this->convite_token);
        $stmt->bindParam(":convite_status", $this->convite_status);

        // Executa a query
        return $stmt->execute();
    }

    // Marca Convites Enviados
    public function marcarConviteEnviado($id) {

        $query = "UPDATE usuarios 
                SET convite_status = 'enviado', convite_enviado_em = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    // Buscar Token
    public function buscarPorToken($token) {

        $query = "SELECT * FROM usuarios WHERE convite_token = :token LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Ativa Conta
    public function ativarConta() {

        $query = "UPDATE usuarios 
                SET senha = :senha,
                    convite_status = 'aceito',
                    convite_token = NULL
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":senha", $this->senha);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

}

?>