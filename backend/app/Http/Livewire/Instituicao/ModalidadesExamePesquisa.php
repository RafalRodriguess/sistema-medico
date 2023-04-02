<?php

namespace App\Http\Livewire\Instituicao;

use App\ModalidadeExame;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;


class ModalidadesExamePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $modalidades;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        // config(["auth.defaults.guard" => "admin"]);
        // $this->authorize('habilidade_admin', 'visualizar_procedimentos');
        $this->performQuery();

        return view('livewire.instituicao.modalidades-exame-pesquisa', [
            'modalidades' => $this->modalidades
        ]);
    }

    private function performQuery() {
        // Buscando somente os registros das instituições cujo usuario está inserido
        $usuario = request()->user('instituicao');
        $this->modalidades = ModalidadeExame::query()->search($this->pesquisa)
                                                    ->whereIn('instituicao_id', $usuario->instituicao->pluck('id'))
                                                    ->whereNull('deleted_at')
                                                    ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
