<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Apenas renderiza a view
        $this->view('home/index', [
            'title' => 'Página inicial',
            'user' => $_SESSION['user'] ?? null
        ]);
    }
}
