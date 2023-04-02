<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class ModelAceitaGanchos extends Model
{
    /**
     * Retorna a instituicao que o model pertence
     */
    public abstract function instituicao() : Relation;

    /**
     * Retorna a tabela do model
     */
    public function getTable()
    {
        return $this->table;
    }
}
