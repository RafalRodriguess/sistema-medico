<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use function Clue\StreamFilter\fun;

class LogInstituicao extends Model
{
    protected $table = 'log_instituicao';

    protected $fillable = [
        'instituicao_id',
        'usuario_id',
        'usuario_type',
        'descricao',
        'dados',
        'registro_type',
        'registro_id',
    ];

    protected $casts = [
    'dados' => 'array',
    ];

    protected $hidden = [
    'dados',
    ];

    public function usuario() {
    return $this->morphTo();
    }

    public function registro() {
    return $this->morphTo();
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamentos::class, 'registro_id')->withTrashed();
    }
    
    public function contaReceber()
    {
        return $this->belongsTo(ContaReceber::class, 'registro_id')->withTrashed();
    }
    
    public function contaPagar()
    {
        return $this->belongsTo(ContaPagar::class, 'registro_id')->withTrashed();
    }
    
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'registro_id')->withTrashed();
    }
    
    public function usuarioEditado()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'registro_id')->withTrashed();
    }

    public function usuarios()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }

    public function odontologico()
    {
        return $this->belongsTo(OdontologicoPaciente::class, 'registro_id')->withTrashed();
    }
    
    public function odontologicoItem()
    {
        return $this->belongsTo(OdontologicoItemPaciente::class, 'registro_id')->withTrashed();
    }

    public function scopeGetDados(Builder $query, $dados):Builder
    {   
        // dd($dados);
        $query->where('registro_type', 'like', "%{$dados['tipo']}%");
        $query->where('usuario_type', 'like', '%InstituicaoUsuario%');
        $query->when($dados['registro_id'], function($q) use($dados){
            $q->where('registro_id', $dados['registro_id']);
        });
        // dd($dados);
        $query->whereBetween('created_at', [date('Y-m-d H:i:s', strtotime($dados['data_inicio'].' 00:00:00')), date('Y-m-d H:i:s', strtotime($dados['data_fim'].' 23:59:59'))]);

        // $query->with('agendamento', 'usuarios', 'agendamento.pessoa');

        return $query;
    }
}
