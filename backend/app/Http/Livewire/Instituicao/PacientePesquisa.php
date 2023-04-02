<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PacientePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private  $pacientes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_paciente');
        
        $this->performQuery();

        return view('livewire.instituicao.paciente-pesquisa', [
            'pacientes' => $this->pacientes,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicaoPaciente()
                    ->search($this->pesquisa);
        $this->pacientes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
