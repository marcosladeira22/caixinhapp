<?php
namespace Core;

/**
 * Paginação profissional do sistema
 */
class Paginator
{
    public readonly int $paginaAtual;
    public readonly int $porPagina;
    public readonly int $totalRegistros;
    public readonly int $totalPaginas;
    public readonly int $offset;

    public function __construct(
        int $totalRegistros,
        int $paginaAtual = 1,
        int $porPagina = 10
    ) {
        $this->totalRegistros = max(0, $totalRegistros);
        $this->porPagina      = max(1, $porPagina);
        $this->paginaAtual    = max(1, $paginaAtual);
        $this->totalPaginas   = (int) ceil($this->totalRegistros / $this->porPagina);
        $this->offset         = ($this->paginaAtual - 1) * $this->porPagina;
    }

    public function temResultados(): bool
    {
        return $this->totalRegistros > 0;
    }
}