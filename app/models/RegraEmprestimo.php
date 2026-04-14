<?php

class RegraEmprestimo {

    private $conn;
    private $table = "regras_emprestimo";

    public $id;
    public $grupo_id;
    public $valor_minimo;
    public $valor_maximo;
    public $juros_inicial_tipo;
    public $juros_inicial_valor;
    public $juros_atraso_tipo;
    public $juros_atraso_valor;
    public $dias_tolerancia;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar regra do grupo
    public function buscarPorGrupo($grupo_id) {

        $query = "SELECT * FROM " . $this->table . "
                  WHERE grupo_id = :grupo_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar regra
    public function criar() {

        $query = "INSERT INTO " . $this->table . "
            (grupo_id, valor_minimo, valor_maximo, juros_inicial_tipo, juros_inicial_valor, juros_atraso_tipo, juros_atraso_valor, dias_tolerancia)
            VALUES
            (:grupo_id, :valor_minimo, :valor_maximo, :juros_inicial_tipo, :juros_inicial_valor, :juros_atraso_tipo, :juros_atraso_valor, :dias_tolerancia)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam(":valor_minimo", $this->valor_minimo);
        $stmt->bindParam(":valor_maximo", $this->valor_maximo);
        $stmt->bindParam(":juros_inicial_tipo", $this->juros_inicial_tipo);
        $stmt->bindParam(":juros_inicial_valor", $this->juros_inicial_valor);
        $stmt->bindParam(":juros_atraso_tipo", $this->juros_atraso_tipo);
        $stmt->bindParam(":juros_atraso_valor", $this->juros_atraso_valor);
        $stmt->bindParam(":dias_tolerancia", $this->dias_tolerancia);

        return $stmt->execute();
    }

    // Atualizar regra
    public function atualizar() {

        $query = "UPDATE " . $this->table . "
            SET valor_minimo = :valor_minimo,
                valor_maximo = :valor_maximo,
                juros_inicial_tipo = :juros_inicial_tipo,
                juros_inicial_valor = :juros_inicial_valor,
                juros_atraso_tipo = :juros_atraso_tipo,
                juros_atraso_valor = :juros_atraso_valor,
                dias_tolerancia = :dias_tolerancia
            WHERE grupo_id = :grupo_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam(":valor_minimo", $this->valor_minimo);
        $stmt->bindParam(":valor_maximo", $this->valor_maximo);
        $stmt->bindParam(":juros_inicial_tipo", $this->juros_inicial_tipo);
        $stmt->bindParam(":juros_inicial_valor", $this->juros_inicial_valor);
        $stmt->bindParam(":juros_atraso_tipo", $this->juros_atraso_tipo);
        $stmt->bindParam(":juros_atraso_valor", $this->juros_atraso_valor);
        $stmt->bindParam(":dias_tolerancia", $this->dias_tolerancia);

        return $stmt->execute();
    }
}