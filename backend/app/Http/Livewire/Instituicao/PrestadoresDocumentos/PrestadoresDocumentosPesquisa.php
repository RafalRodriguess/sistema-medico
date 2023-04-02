<?php

namespace App\Http\Livewire\Instituicao\PrestadoresDocumentos;

use App\Prestador;
use App\Instituicao;
use App\DocumentoPrestador;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PrestadoresDocumentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $prestador;

    public $instituicao;

    private  $prestador_documentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request, $prestador)
    {
        $this->prestador = $prestador;
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_documento_prestador');
        $this->performQuery();
        return view('livewire.instituicao.prestadores-documentos.prestadores-documentos-pesquisa', [
            'prestador_documentos' => $this->prestador_documentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->prestador->instituicaoPrestador($this->instituicao->id)->documentos();

        $this->prestador_documentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}

