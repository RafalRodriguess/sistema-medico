<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination; 
 
class TipoComprasPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $tipo_compras;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipo_compras');
        $this->performQuery();  
        return view('livewire.instituicao.tipo-compras-pesquisa', [
            'tipo_compras' => $this->tipo_compras,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->tipoCompras()->search($this->pesquisa);
        $this->tipo_compras = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
