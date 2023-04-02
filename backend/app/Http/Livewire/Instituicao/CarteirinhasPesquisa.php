<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Pessoa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CarteirinhasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    public $pessoa;

    private $carteirinha;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'tipo' => ['except' => 0],
    ];

    public function mount(Request $request, Pessoa $pessoa)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $this->pessoa = $pessoa;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_carteirinha');
        
        $this->performQuery();

        return view('livewire.instituicao.carteirinhas-pesquisa', [
            'carteirinhas' => $this->carteirinha
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->pessoa->carteirnha()->search($this->pesquisa);
   
        $this->carteirinha = $query->paginate(15);
    }

}
