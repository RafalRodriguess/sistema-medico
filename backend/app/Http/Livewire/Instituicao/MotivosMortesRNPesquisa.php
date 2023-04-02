<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MotivosMortesRNPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $motivosMortesRN;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn');
        $this->performQuery();

        return view('livewire.instituicao.motivos-mortes-r-n-pesquisa', [
            'motivosMorteRN' => $this->motivosMortesRN,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->motivosMortesRN()->search($this->pesquisa);
        $this->motivosMortesRN = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
