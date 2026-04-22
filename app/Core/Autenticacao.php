<?php
namespace Core;

class Autenticacao
{
    
    public static function verificar()
    {
        // ✅ Garante que a sessão esteja ativa
        Sessao::iniciar();

        // ✅ Verifica se o usuário está logado
        if (!Sessao::get('usuario_id')) {
            header('Location: ' . base_url('?rota=auth@login'));
            exit;
        }
    }

}