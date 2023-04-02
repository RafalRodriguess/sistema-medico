<?php

namespace App\Http\Livewire\Instituicao;

use App\Avaliacao;
use App\Instituicao;
use App\Prestador;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class AvalicoesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $paciente_id_avaliacao = 0;
    public $medico_id_avaliacao = 0;
    public $especialidade_id_avaliacao = 0;
    public $atendidos = 0;

    private $avaliacoes;
    private $instituicao;

    protected $updatesQueryString = [
        'paciente_id_avaliacao' => ['except' => 0],
        'medico_id_avaliacao' => ['expect' => 0],
        'especialidade_id_avaliacao' => ['except' => 0],
        'atendidos' => ['except' => 0],
    ];

    protected $listeners = [
        'pacienteIdAvaliacao' => 'updatingPacienteIdAvaliacao',
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) {
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$this->instituicao->id);
        })->get();
        $especialidades = $this->instituicao->especialidadesInstituicao()->get();


        return view('livewire.instituicao.avalicoes-pesquisa', [
            'avaliacoes' => $this->avaliacoes,
            'medicos' => $medicos,
            'especialidades' => $especialidades
        ]);
    }

    private function performQuery()
    {
        $this->instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $instituicao = $this->instituicao;
        
        $query = Avaliacao::whereHas('agendamento.internacao', function($q) use($instituicao){
            $q->where('instituicao_id', $instituicao->id);
        });
        
        if($this->paciente_id_avaliacao != 0){
            $query->where('paciente_id', $this->paciente_id_avaliacao);
        }

        if($this->medico_id_avaliacao != 0){
            $query->where('medico_id', $this->medico_id_avaliacao);
        }

        if($this->especialidade_id_avaliacao != 0){
            $query->where('especialidade_id', $this->especialidade_id_avaliacao);
        }

        if($this->atendidos != 2){
            $query->where('atendido', $this->atendidos);
        }

        $this->avaliacoes = $query->paginate(15);
    }

    public function updatingPacienteIdAvaliacao($id): void
    {
        $this->paciente_id_avaliacao = $id;
        $this->resetPage();
    }

    public function updatingMedicoIdAvaliacao(): void
    {
        $this->resetPage();
    }

    public function updatingEspecialidadeIdAvaliacao(): void
    {
        $this->resetPage();
    }
}
