<?php

namespace App\Hooks\Interfaces;

/**
 * Define os models que podem disparar um gancho
 */
interface ModelDisparaGanchos 
{
    /**
     * Retorna uma instância da interface Gancho a forma
     * recomendada de fazer tal coisa é utilizando o método make
     * da classe GanchoModel
     * @return Gancho|null
     */
    public function gancho();

    /**
     * Método que deve retornar o nome da classe que será
     * alvo do gancho
     * @return string
     */
    public function modelAlvo() : string;
}