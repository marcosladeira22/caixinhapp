<?php

// Define o namespace da classe
namespace App\Models;

// Classe responsável pelos dados do usuário.
class User
{
    // Método de exemplo
    public function getAll()
    {
        // Retorna dados simulados
        // No futuro isso virá do banco
        return [
            ['name' => 'Marcos'],
            ['name' => 'Ana'],
            ['name' => 'Carlos']
        ];
    }
}
