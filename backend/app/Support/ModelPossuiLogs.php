<?php

namespace App\Support;

use App\Log;
use App\LogInstituicao;

trait ModelPossuiLogs {

    public function logs() {
        return $this->morphMany(Log::class, "registro");
    }

    public function logsinstituicao() {
        return $this->morphMany(LogInstituicao::class, "registro");
    }

    public function criarLog($usuario, $descricao, $dados = null, $comercialId = null) {
        return $this->logs()->create([
            'comercial_id' => $comercialId,
            'usuario_id' => $usuario->id,
            'usuario_type' => get_class($usuario),
            'descricao' => $descricao,
            'dados' => $dados,
        ]);
    }

    public function criarLogInstituicao($usuario, $descricao, $dados = null, $insitituicaoId = null) {
        return $this->logsinstituicao()->create([
            'instituicao_id' => $insitituicaoId,
            'usuario_id' => $usuario->id,
            'usuario_type' => get_class($usuario),
            'descricao' => $descricao,
            'dados' => $dados,
        ]);
    }

    public function criarLogCadastro($usuario, $comercialId = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(), $comercialId);
    }

    public function criarLogEdicao($usuario, $comercialId = null) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges(), $comercialId);
    }

    public function criarLogExclusao($usuario, $comercialId = null) {
        return $this->criarLog($usuario, 'Exclusão', $this->getAttributes(), $comercialId);
    }

    public function criarLogInstituicaoCadastro($usuario, $instituicaoId = null) {
        return $this->criarLogInstituicao($usuario, 'Cadastro', $this->getAttributes(), $instituicaoId);
    }

    public function criarLogInstituicaoEdicao($usuario, $instituicaoId = null) {
        return $this->criarLogInstituicao($usuario, 'Edição', $this->getChanges(), $instituicaoId);
    }

    public function criarLogInstituicaoExclusao($usuario, $instituicaoId = null) {
        return $this->criarLogInstituicao($usuario, 'Exclusão', $this->getAttributes(), $instituicaoId);
    }
}
