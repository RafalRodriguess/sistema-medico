<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CartoesCreditoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $cartoesCredito;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_cartao_credito');
        $this->performQuery();
        
        return view('livewire.instituicao.cartoes-credito-pesquisa', [
            'cartoesCredito' => $this->cartoesCredito,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->cartoesCredito()->search($this->pesquisa);
        //dd($query);
        $this->cartoesCredito = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
