<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Pessoa;
use App\PessoaDocumento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class FornecedoresDocumentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $tipo = 0;

    public $instituicao;

    public $fornecedor;

    private $fornecedor_documentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'tipo' => ['except' => 0],
    ];

    public function mount(Request $request, Pessoa $fornecedor)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $this->fornecedor = $fornecedor;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_documentos_fornecedores');
        
        $this->performQuery();

        return view('livewire.instituicao.fornecedores-documentos-pesquisa', [
            'documentos' => $this->fornecedor_documentos,
            'tipos_documentos' => PessoaDocumento::getTipos(),
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->fornecedor->documentos()
            ->searchByTipo($this->tipo)
            ->searchByDescricao($this->pesquisa);
   
        $this->fornecedor_documentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingTipo(): void
    {
        $this->resetPage();
    }

}
