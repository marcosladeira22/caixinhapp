<?php
namespace Core;

class Paginator
{
    public int $paginaAtual;
    public int $porPagina;
    public int $totalRegistros;
    public int $totalPaginas;
    public int $offset;

    public function __construct(int $totalRegistros, int $paginaAtual = 1, int $porPagina = 10)
    {
        $this->totalRegistros = $totalRegistros;
        $this->porPagina      = $porPagina;
        $this->paginaAtual    = max(1, $paginaAtual);
        $this->totalPaginas   = (int) ceil($totalRegistros / $porPagina);
        $this->offset         = ($this->paginaAtual - 1) * $porPagina;
    }

    public function temResultados(): bool
    {
        return $this->totalRegistros > 0;
    }
}