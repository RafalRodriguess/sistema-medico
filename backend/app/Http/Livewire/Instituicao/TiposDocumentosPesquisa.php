<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class TiposDocumentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $tiposDocumentos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipos_documentos');
        $this->performQuery();
        //dd($this->tiposDocumentos);
        return view('livewire.instituicao.tipos-documentos-pesquisa', [
            'tiposDocumentos' => $this->tiposDocumentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->tiposDocumentos()->search($this->pesquisa);
        $this->tiposDocumentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
