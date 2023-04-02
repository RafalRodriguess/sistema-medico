<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class NotaFiscal extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'notas_fiscais';

    protected $fillable = [
        'id',
        'instituicao_id',
        'contas_receber_id',
        'pessoa_id',
        'status',
        'aliquota_iss',
        'valor_iis',
        'iss_retido_fonte',
        'cnae',
        'valor_pis',
        'p_pis',
        'valor_confis',
        'p_cofins',
        'valor_inss',
        'p_inss',
        'valor_ir',
        'p_ir',
        'uf_prestacao_servico',
        'municipio_prestacao_servico',
        'descricao',
        'cod_servico_municipal',
        'descricao_servico_municipal',
        'natureza_operacao',
        'deducoes',
        'descontos',
        'valor_total',
        'observacoes',
        "cliente_nome",
        "cliente_email",
        "cliente_cpfCnpj",
        "cliente_inscricaoMunicipal",
        "cliente_inscricaoEstadual",
        "cliente_telefone",
        
        "cliente_pais",
        "cliente_uf",
        "cliente_cidade",
        "cliente_logradouro",
        "cliente_numero",
        "cliente_complemento",
        "cliente_bairro",
        "cliente_cep",
        "id_nfse_enotas",
        "motivo_status",
        "json_nfe",
        "xml_nfe",
    ];

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function contaReceber()
    {
        return $this->belongsTo(ContaReceber::class, 'id', 'nota_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
