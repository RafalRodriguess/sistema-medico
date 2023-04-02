<?php

namespace App\Http\Livewire\Admin;

use App\Instituicao;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class InstituicaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $instituicoes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_instituicao');
        $this->performQuery();
        $this->instituicoes->each(function($instituicao) {
            $instituicao->qtdAgenda = 0;
            $instituicao->prestadoresQtd()->get()->each(function($prestador) use($instituicao){
                $prestador->especialidadeInstituicao()->where('instituicoes_id', $instituicao->id)->get()->each(function($especialidade) use($instituicao){
                    $instituicao->qtdAgenda += $especialidade->agenda()->count();
                });
            });
        });
        return view('livewire.admin.instituicao-pesquisa', [
            'instituicoes' => $this->instituicoes
        ]);
    }

    private function performQuery(): void
    {
        $query = Instituicao::query()
                    ->search($this->pesquisa);
        $this->instituicoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
