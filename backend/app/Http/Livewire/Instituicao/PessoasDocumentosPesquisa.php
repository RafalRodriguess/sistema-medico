<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Pessoa;
use App\PessoaDocumento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PessoasDocumentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $tipo = 0;

    public $instituicao;

    public $pessoa;

    private $pessoa_documentos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_documentos_pessoas');
        
        $this->performQuery();

        return view('livewire.instituicao.pessoas-documentos-pesquisa', [
            'documentos' => $this->pessoa_documentos,
            'tipos_documentos' => PessoaDocumento::getTipos(),
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->pessoa->documentos()
            ->searchByTipo($this->tipo)
            ->searchByDescricao($this->pesquisa);
   
        $this->pessoa_documentos = $query->paginate(15);
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
