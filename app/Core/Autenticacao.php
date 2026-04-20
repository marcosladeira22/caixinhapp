<?php
namespace Core;

class Autenticacao
{
    public static function verificar()
    {
        if (!Sessao::get('usuario_id')) {
            header('Location: /?rota=auth@login');
            exit;
        }
    }
}