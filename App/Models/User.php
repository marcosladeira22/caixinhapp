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

        // RETORNA O ID DO USUÁRIO CRIADO
        return $this->db->lastInsertId();
        
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
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");

        return $stmt->execute([
            'id'    => $id,
            'name'  => $name,
            'email' => $email
        ]);
    }

    // DELETE — remove usuário
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    /**
     * HARD DELETE — remove definitivamente do banco
     */
    public function forceDelete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    public function updatePassword($id, $password)
    {
        // SQL para atualizar apenas a senha
        $sql = "UPDATE users SET password = :password WHERE id = :id";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Associa os valores
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        // Executa
        return $stmt->execute();
    }

    public function findByEmail($email)
    {
        // SQL para buscar usuário pelo email
        $sql = "SELECT * FROM users
        WHERE email = :email
        AND deleted_at IS NULL
        LIMIT 1";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Associa o email
        $stmt->bindValue(':email', $email);

        // Executa
        $stmt->execute();

        // Retorna os dados do usuário
        return $stmt->fetch();
    }

    public function findByEmailIncludingInactive($email)
    {
        // SQL busca usuário ativo OU desativado
        $sql = "SELECT * FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

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

    // Conta apenas usuários ativos
    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM users WHERE deleted_at IS NULL";
        return $this->db->query($sql)->fetchColumn();
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
            WHERE deleted_at IS NULL
            AND (name LIKE :search OR email LIKE :search)
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

        if (!in_array($order, $allowedOrders)) {
            $order = 'id';
        }

        if (!in_array($dir, $allowedDir)) {
            $dir = 'desc';
        }

        // Busca somente usuários ativos
        $sql = "
            SELECT *
            FROM users
            WHERE deleted_at IS NULL
            ORDER BY $order $dir
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Desativa um usuário (soft delete)
    public function softDelete($id)
    {
        $sql = "
            UPDATE users
            SET deleted_at = NOW()
            WHERE id = :id
            AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    // Reativa um usuário desativado
    public function restore($id)
    {
        $sql = "
            UPDATE users
            SET deleted_at = NULL
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    // Retorna usuários desativados (soft deleted)
    public function getDeleted()
    {
        // SQL para buscar usuários que possuem deleted_at preenchido
        $sql = "
            SELECT *
            FROM users
            WHERE deleted_at IS NOT NULL
            ORDER BY deleted_at DESC
        ";

        // Executa e retorna os resultados
        return $this->db->query($sql)->fetchAll();
    }

   public function uploadAvatar($userId, $file)
    {
        // Tipos de imagem permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Valida tipo do arquivo
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        // Extensão do arquivo original
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Nome único para evitar conflitos
        $filename = uniqid('avatar_') . '.' . $extension;

        // Diretório físico real
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/caixinhapp/public/uploads/avatars/';

        // Cria diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Caminho final do arquivo
        $destination = $uploadDir . $filename;

        // Move o arquivo para o destino
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return false;
        }

        // Atualiza o nome do avatar no banco
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':avatar', $filename);
        $stmt->bindValue(':id', $userId);

        return $stmt->execute();
    }

}
