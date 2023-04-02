<?php

namespace App\Http\Livewire\Instituicao;

use App\AtendimentoPaciente;
use App\Instituicao;
use App\Pessoa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AtendimentoPacientePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';
    public $usuarioAtendeu = '';

    private $atendimentoPaciente;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'usuarioAtendeu' => ['except' => ''],
    ];

    public $pessoa;

    public $instituicao;
    
    public function mount(Request $request, Pessoa $pessoa)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->pessoa = $pessoa;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        //$this->authorize('habilidade_instituicao_sessao', 'visualizar_cirurgias');
        $this->performQuery();
        $usuarios = $this->instituicao->instituicaoUsuarios()->get();
        $motivos = $this->instituicao->motivoAtendimento()->get();
        return view('livewire.instituicao.atendimento-paciente-pesquisa', [
            'atendimentoPaciente' => $this->atendimentoPaciente,
            'usuarios' => $usuarios,
            'motivos' => $motivos,
            'pessoaPesquisa' => $this->pessoa
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->atendimentoPaciente()->search($this->pesquisa, $this->pessoa->id, $this->usuarioAtendeu)->orderBy('created_at', 'DESC');
        $this->atendimentoPaciente = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingUsuarioAtendeu(): void
    {
        $this->resetPage();
    }
}
