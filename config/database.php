<?php

class Database {

    private $host     = "localhost";  //endereço do banco
    private $port     = "33306";      //porta do MySQL  
    private $db_name  = "caixinhapp_db"; //nome do banco
    private $username = "root";       //usuário do banco
    private $password = "mvsl@22";     //senha do banco
    public $conn;

    //Método que cria a conexão
    public function getConnection() {
        $this->conn = null;

        try {
            //Criando conexão PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . 
                ";port=" . $this->port . 
                ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            //Define charset UTF-8
            $this->conn->exec("set names utf8");

        } catch (PDOException $exception) {
            //Caso dê erro
            echo "Erro de conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>