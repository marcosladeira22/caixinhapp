<?php

// Define o namespace da classe
namespace App\Models;
use Core\Database;
use PDO;

// Classe responsável pelos dados do usuário.
class User
{
    // Conexão com o banco
    private $db;

    public function __construct()
    {
        // Pega a conexão PDO
        $this->db = Database::getConnection();
    }

    // CREATE — insere usuário
    public function create($name, $email, $password, $role = 'user')
    {
        // SQL para inserir usuário
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        
        // Prepara a query (proteção contra SQL Injection)
        $stmt = $this->db->prepare($sql);

        // Executa a query com dados seguros
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':role', $role);

        // Executa no banco
        $stmt->execute();
    }

    // READ — lista todos
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ — busca um usuário
    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE — atualiza usuário
    public function update($id, $name, $email)
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = :name, email = :email WHERE id = :id"
        );

        return $stmt->execute([
            'id'    => $id,
            'name'  => $name,
            'email' => $email
        ]);
    }

    // DELETE — remove usuário
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }

    public function findByEmail($email)
    {
        // SQL para buscar usuário pelo email
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Associa o email
        $stmt->bindValue(':email', $email);

        // Executa
        $stmt->execute();

        // Retorna os dados do usuário
        return $stmt->fetch();
    }

    // Verifica se um email já existe no banco
    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        // Retorna true se encontrar algum registro
        return $stmt->fetch() ? true : false;
    }

    // Retorna usuários paginados
    public function getPaginated($limit, $offset)
    {
        // SQL com LIMIT e OFFSET
        $sql = "
            SELECT *
            FROM users
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // LIMIT e OFFSET precisam ser inteiros
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

        // Executa
        $stmt->execute();

        // Retorna todos os registros
        return $stmt->fetchAll();
    }


    // Retorna o total de usuários
    public function countAll()
    {
        // SQL para contar registros
        $sql = "SELECT COUNT(*) AS total FROM users";

        // Executa a query
        $stmt = $this->db->query($sql);

        // Retorna o total
        return $stmt->fetch()['total'];
    }

    // Busca usuários aplicando filtro e paginação
    public function searchPaginated($search, $limit, $offset)
    {
        // SQL com filtro por nome ou email
        $sql = "
            SELECT *
            FROM users
            WHERE name LIKE :search
            OR email LIKE :search
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Aplica o termo de busca com curingas
        $stmt->bindValue(':search', '%' . $search . '%');

        // LIMIT e OFFSET precisam ser inteiros
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

        // Executa a consulta
        $stmt->execute();

        // Retorna os registros encontrados
        return $stmt->fetchAll();
    }


    // Conta quantos usuários existem com o filtro aplicado
    public function countSearch($search)
    {
        // SQL de contagem com o mesmo filtro
        $sql = "
            SELECT COUNT(*) AS total
            FROM users
            WHERE name LIKE :search
            OR email LIKE :search
        ";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Aplica o termo de busca
        $stmt->bindValue(':search', '%' . $search . '%');

        // Executa
        $stmt->execute();

        // Retorna o total encontrado
        return $stmt->fetch()['total'];
    }

    // Busca usuários com filtro, ordenação e paginação
    public function searchPaginatedOrdered($search, $order, $dir, $limit, $offset)
    {
        // Colunas permitidas (SEGURANÇA contra SQL Injection)
        $allowedOrders = ['id', 'name', 'email'];

        // Direções permitidas
        $allowedDir = ['asc', 'desc'];

        // Valida coluna de ordenação
        if (!in_array($order, $allowedOrders)) {
            $order = 'id';
        }

        // Valida direção
        if (!in_array($dir, $allowedDir)) {
            $dir = 'desc';
        }

        // SQL com filtro e ordenação
        $sql = "
            SELECT *
            FROM users
            WHERE name LIKE :search
            OR email LIKE :search
            ORDER BY $order $dir
            LIMIT :limit OFFSET :offset
        ";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Termo de busca
        $stmt->bindValue(':search', '%' . $search . '%');

        // Paginação
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

        // Executa
        $stmt->execute();

        // Retorna os usuários
        return $stmt->fetchAll();
    }

    // Busca usuários com ordenação e paginação (sem filtro)
    public function getPaginatedOrdered($order, $dir, $limit, $offset)
    {
        // Colunas permitidas
        $allowedOrders = ['id', 'name', 'email'];
        $allowedDir = ['asc', 'desc'];

        // Valida
        if (!in_array($order, $allowedOrders)) {
            $order = 'id';
        }

        if (!in_array($dir, $allowedDir)) {
            $dir = 'desc';
        }

        // SQL
        $sql = "
            SELECT *
            FROM users
            ORDER BY $order $dir
            LIMIT :limit OFFSET :offset
        ";

        // Prepara
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }


}
