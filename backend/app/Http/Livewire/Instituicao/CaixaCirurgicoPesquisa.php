<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CaixaCirurgicoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $caixasCirurgicos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_caixas_cirurgicos');
        $this->performQuery();
        
        return view('livewire.instituicao.caixa-cirurgico-pesquisa', [
            'caixasCirurgicos' => $this->caixasCirurgicos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->caixasCirurgicos()->search($this->pesquisa);
        //dd($query);
        $this->caixasCirurgicos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
