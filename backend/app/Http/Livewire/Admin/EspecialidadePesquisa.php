<?php

namespace App\Http\Livewire\Admin;

use App\Especialidade;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EspecialidadePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';


    private  $especialidades;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];



    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_especialidade');

        $this->performQuery();

        return view('livewire.admin.especialidade-pesquisa', [
            'especialidades' => $this->especialidades,
        ]);
    }

    private function performQuery(): void
    {
        $query = Especialidade::search($this->pesquisa);
        $this->especialidades = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
