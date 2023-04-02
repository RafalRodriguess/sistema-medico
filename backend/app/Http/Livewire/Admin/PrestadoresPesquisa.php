<?php

namespace App\Http\Livewire\Admin;

use App\Instituicao;
use App\Prestador;
use App\Especialidade;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;

class PrestadoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $especialidade = 0;

    public $instituicao;

    private  $prestador;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'especialidade' => ['except' => 0],
    ];


    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_prestador');

        $this->performQuery();


        return view('livewire.admin.prestadores-pesquisa', [
            'prestador' => $this->prestador
        ]);
    }

    private function performQuery(): void
    {
        $query = Prestador::search($this->pesquisa)->withCount(['instituicoes' => function($q){
            $q->select(DB::raw('count(distinct(instituicoes.id))'));
        }]);
        $this->prestador = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingEspecialidade(): void
    {
        $this->resetPage();
    }

}
