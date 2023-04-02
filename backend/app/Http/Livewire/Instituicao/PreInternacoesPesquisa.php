<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PreInternacoesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $medico_id = 0;

    public $paciente_id = 0;
    
    public $pesquisa = '';

    public $instituicao;

    private $preInternacao;

    protected $updatesQueryString = [
        'pesquisa' => [ 'except' => '', ],
        'paciente_id' => [ 'except' => 0 ] ,
        'medico_id' => [ 'except' => 0]
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pre_internacao');
        $this->performQuery();

        $pacientes =  $this->instituicao->instituicaoPessoas()->where('tipo', '2')->get();
        $medicos =  $this->instituicao->prestadores()->with('prestador')->where('tipo', '2')->get();

        return view('livewire.instituicao.pre-internacoes-pesquisa', [
            'preInternacoes' => $this->preInternacao,
            'pacientes' =>  $pacientes,
            'medicos' => $medicos
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->preInternacoes()->search($this->pesquisa, $this->paciente_id, $this->medico_id)->orderBy('id', 'DESC');
        $this->preInternacao = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingMedicoId(): void
    {
        $this->resetPage();
    }

    public function updatingPacienteId(): void
    {
        $this->resetPage();
    }
}
