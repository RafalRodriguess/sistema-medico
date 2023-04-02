<?php

namespace App;

use App\Support\ModelOverwrite;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicoesPrestadores extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    use ModelOverwrite;

    protected $table = 'instituicoes_prestadores';

    protected $fillable = [
        'prestadores_id',
        'instituicoes_id',
        'ativo',
        'tipo',
        'tipo_conselho_id',
        'conselho_uf',
        'anestesista',
        'auxiliar',
        'vinculos',
        'carga_horaria_mensal',
        'pis',
        'pasep',
        'nir',
        'proe',
        'numero_cooperativa',
        'nome_banco',
        'agencia',
        'conta_bancaria',
        'especialidade_id',
        'instituicao_usuario_id',
        'tipo_prontuario',
        'crm',
        'telefone',
        'exibir_data',
        'telefone2',
        'whatsapp_enviar_confirm_agenda',
        'whatsapp_receber_agenda',
        'exibir_titulo_paciente',
        'resumo_tipo',
        'telemedicina_integrado',
        'nome',
    ];

    protected $casts = [
        'vinculos' => 'array',
        'exibir_data' => 'boolean',
        'telemedicina_integrado' => 'boolean',
    ];

    protected $allowed_overwrite = [
        InstPrestEspecializacao::class,
    ];

    // Tipos
    const residente = 1;
    const medico = 2;
    const enfermeiro = 3;
    const tecnico_em_enfermagem = 4;
    const academico_de_medicina = 5;
    const fisioterapeuta = 6;
    const fonoaudiologo = 7;
    const psicologo = 8;
    const terapeuta_ocupacional = 9;
    const nutricionista = 10;
    const biomedico = 11;
    const tecnico_em_laboratório = 12;
    const farmaceutico = 13;
    const tecnico_em_farmacia = 14;
    const local = 15;
    //

    public static function getTipos()
    {
        return [
            self::residente,
            self::medico,
            self::enfermeiro,
            self::tecnico_em_enfermagem,
            self::academico_de_medicina,
            self::fisioterapeuta,
            self::fonoaudiologo,
            self::psicologo,
            self::terapeuta_ocupacional,
            self::nutricionista,
            self::biomedico,
            self::tecnico_em_laboratório,
            self::farmaceutico,
            self::tecnico_em_farmacia,
            self::local,
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::residente => 'Residente',
            self::medico => 'Médico',
            self::enfermeiro => 'Enfermeiro',
            self::tecnico_em_enfermagem => 'Técnico em Enfermagem',
            self::academico_de_medicina => 'Acadêmico de Medicina',
            self::fisioterapeuta => "fisioterapeuta",
            self::fonoaudiologo => "fonoaudiólogo",
            self::psicologo => "psicologo",
            self::terapeuta_ocupacional => "terapeuta ocupacional",
            self::nutricionista => "nutricionista",
            self::biomedico => "biomédico",
            self::tecnico_em_laboratório => "técnico em laboratório",
            self::farmaceutico => "farmacêutico",
            self::tecnico_em_farmacia => "técnico em farmácia",
            self::local => "Local",
        ];
        if ($tipo) return $dados[$tipo];
    }

    // Especialidades Médicas
    const clinica_medica = 1;
    const geriatria = 2;
    const oftamologia = 3;
    const oncologia = 4;
    const dermatologia = 5;
    const cardiologia = 6;
    const infectologia = 7;
    const cirurgia = 8;
    //

    // Vinculos
    const tipos_vinculo = [
        1 => 'cooperado',
        2 => 'funcionario',
        3 => 'estagiario',
        4 => 'voluntario',
        5 => 'pessoa física',
        6 => 'pessoa jurídica',
    ];
    //

    // Personalidade
    const pessoa_fisica = 1;
    const pessoa_juridica = 2;
    //

    // Tipos de Conselhos
    const tipos_conselhos = [
        1  => 'CRM',
        2  => 'CFM',
        3  => 'CRO',
        4  => 'CFO',
        5  => 'Conselho Regional dos Fonoaudiólogos',
        6  => 'COREN',
        7  => 'CRA',
        8  => 'CRBIO',
        9  => 'CRBM',
        10 => 'CREFITO',
        11 => 'CRESS',
        12 => 'CRF',
        13 => 'CRN',
        14 => 'CRO',
        15 => 'CRP',
        16 => 'CRTR',
        17 => 'FT',
        18 => 'Psicólogo',
    ];

    public static function getTiposConselhos()
    {
        return array_keys(self::tipos_conselhos);
    }

    public static function getTipoConselhoTexto($conselho)
    {
        return self::tipos_conselhos[$conselho];
    }



    public static function getEspecialidades()
    {
        return [
            self::clinica_medica,
            self::geriatria,
            self::oftamologia,
            self::oncologia,
            self::dermatologia,
            self::cardiologia,
            self::infectologia,
            self::cirurgia
        ];
    }

    public static function getEspecialidadeTexto($especialidade)
    {
        $dados = [
            self::clinica_medica => 'Clínica Médica',
            self::geriatria => 'Geriatria',
            self::oftamologia => 'Oftamologia',
            self::oncologia => 'Oncologia',
            self::dermatologia => 'Dermatologia',
            self::cardiologia => 'CCardiologia',
            self::infectologia => 'Infectologia',
            self::cirurgia => 'Cirurgia'
        ];
        return $dados[$especialidade];
    }

    public static function getPersonalidades()
    {
        return [
            self::pessoa_fisica,
            self::pessoa_juridica,
        ];
    }


    public static function getVinculos()
    {
        return array_keys(self::tipos_vinculo);
    }

    public static function getVinculoTexto($vinculo)
    {
        return self::tipos_vinculo[$vinculo];
    }

    public function getEspecialidadesTexto()
    {
        $especialidades_texto = '';
        foreach ($this->especialidades as $especialidade) {
            $especialidades_texto = "$especialidades_texto" . "$especialidade->nome; ";
        }
        return $especialidades_texto;
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'inst_prest_especialidades', 'instituicao_prestador_id', 'especialidade_id');
    }

    public function prestadorInstituicaoEspecialidade()
    {
        return $this->hasMany(InstPrestEspecialidade::class, 'instituicao_prestador_id');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestadores_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class,'especialidade_id');
    }

    public function agenda()
    {
        return $this->hasMany(InstituicoesAgenda::class, 'instituicoes_prestadores_id');
    }

    public function prestadoresProcedimentos()
    {
        return $this->hasMany(ProcedimentosConveniosInstituicoesPrestadores::class, 'instituicoes_prestadores_id');
    }

    public function documentos()
    {
        return DocumentoPrestador::query()->where('instituicao_prestador_id', $this->id);
    }

    public function modeloImpressao()
    {
        return $this->hasOne(ModeloImpressao::class, 'instituicao_prestador_id');
    }
    
    public function modeloAtestado()
    {
        return $this->hasMany(ModeloAtestado::class, 'instituicao_prestador_id');
    }
    
    public function modeloLaudo()
    {
        return $this->hasMany(ModeloLaudo::class, 'instituicao_prestador_id');
    }
    
    public function modeloEncaminhamento()
    {
        return $this->hasMany(ModeloEncaminhamento::class, 'instituicao_prestador_id');
    }
    
    public function modeloRelatorio()
    {
        return $this->hasMany(ModeloRelatorio::class, 'instituicao_prestador_id');
    }
    
    public function modeloExame()
    {
        return $this->hasMany(ModeloExame::class, 'instituicao_prestador_id');
    }
    
    public function modeloReceituario()
    {
        return $this->hasMany(ModeloReceituario::class, 'instituicao_prestador_id');
    }

    public function modeloProntuario()
    {
        return $this->hasMany(ModeloProntuario::class, 'instituicao_prestador_id');
    }

    public function modeloConclusao()
    {
        return $this->hasMany(ModeloConclusao::class, 'instituicao_prestador_id');
    }

    public function scopeSearchPrestadores(Builder $query, string $search = '', int $especialidade): Builder
    {

        if ($especialidade != 0) {
            $query->wherehas('especialidade', function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->wherehas('prestador', function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        return $query->wherehas('prestador', function ($q) use ($search) {
            $q->where('nome', 'like', "%{$search}%");
        });
    }

    public function prestadorEspecializacoes()
    {
        return $this->hasMany(InstPrestEspecializacao::class, 'instituicoes_prestadores_id');
    }

    public function especializacoes()
    {
        return $this->hasManyThrough(Especializacao::class, InstPrestEspecializacao::class, 'instituicoes_prestadores_id', 'id', 'id', 'especializacoes_id');
    }

    public function procedimentosExcessoes()
    {
        return $this->belongsToMany(Procedimento::class, 'excessao_procedimentos_prestador', 'prestador_id', 'procedimento_id')->withPivot('prestador_faturado_id');
    }
}
