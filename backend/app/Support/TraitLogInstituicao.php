<?php

namespace App\Support;

use App\LogInstituicao;

trait TraitLogInstituicao {

    public function logs() {
        return $this->morphMany(LogInstituicao::class, "registro");
    }

    public function criarLog($usuario, $descricao, $dados = null, $instituicaoId = null) {
        return $this->logs()->create([
            'instituicao_id' => $instituicaoId,
            'usuario_id' => $usuario->id,
            'usuario_type' => get_class($usuario),
            'descricao' => $descricao,
            'dados' => $dados,
        ]);
    }

    public function criarLogCadastro($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(), $instituicaoId);
    }

    public function criarLogEdicao($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges(), $instituicaoId);
    }

    public function criarLogExclusao($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Exclusão', $this->getChanges(), $instituicaoId);
    }
  
    public function criarLogCadastroConvenios($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(), $instituicaoId);
    }

    public function criarLogEdicaoConvenios($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges(), $instituicaoId);
    }

    public function criarLogExclusaoConvenios($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Exclusão', $this->getChanges(), $instituicaoId);
    }
  
    public function criarLogCadastroProcedimentos($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(), $instituicaoId);
    }

    public function criarLogEdicaoProcedimentos($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges(), $instituicaoId);
    }

    public function criarLogExclusaoProcedimentos($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Exclusão', $this->getChanges(), $instituicaoId);
    }

    public function criarLogCadastroVinculacao($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(), $instituicaoId);
    }

    public function criarLogEdicaoVinculacao($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges(), $instituicaoId);
    }

    public function criarLogExclusaoVinculacao($usuario, $instituicaoId = null) {
        return $this->criarLog($usuario, 'Exclusão', $this->getAttributes(), $instituicaoId);
    }

}
