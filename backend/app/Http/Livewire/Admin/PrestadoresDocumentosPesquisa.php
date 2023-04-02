<?php

namespace App\Http\Livewire\Admin;

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

    private  $prestador_documentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount($prestador)
    {
        $this->prestador = $prestador;
    }


    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_documento_prestador');
        $this->performQuery();
        return view('livewire.admin.prestadores-documentos-pesquisa', [
            'prestador_documentos' => $this->prestador_documentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->prestador->documentos()->search($this->pesquisa);
        $this->prestador_documentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
