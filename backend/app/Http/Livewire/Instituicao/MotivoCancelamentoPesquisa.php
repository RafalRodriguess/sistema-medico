<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination; 

class MotivoCancelamentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $motivo_cancelamentos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivo_cancelamento');
        $this->performQuery();  
        return view('livewire.instituicao.motivo-cancelamento-pesquisa', [
            'motivo_cancelamentos' => $this->motivo_cancelamentos,
        ]);
    }
     
    private function performQuery(): void
    {
        $query = $this->instituicao->motivo_cancelamentos()->search($this->pesquisa);
        $this->motivo_cancelamentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
