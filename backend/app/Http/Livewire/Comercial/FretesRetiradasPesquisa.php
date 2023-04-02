<?php

namespace App\Http\Livewire\Comercial;


use App\Comercial;
use App\Fretes;
use App\FretesRetirada;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class FretesRetiradasPesquisa  extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $comercial;

    public $frete;

    private  $itens;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->frete = Fretes::where('comercial_id', $request->session()->get('comercial'))->where('tipo_frete', 'retirada')->first()->id;
  
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_categoria');

        $this->performQuery();

        return view('livewire.comercial/fretes-retiradas-pesquisa', [
            'itens' => $this->itens,
        ]);
    }

    private function performQuery(): void
    {
        $query = FretesRetirada::searchFretesFiltros($this->pesquisa, $this->frete);

        $this->itens = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
