<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MotivoAtendimentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private $motivosAtendimento;

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
        //$this->authorize('habilidade_instituicao_sessao', 'visualizar_cirurgias');
        $this->performQuery();

        return view('livewire.instituicao.motivo-atendimento-pesquisa', [
            'motivosAtendimento' => $this->motivosAtendimento,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->motivoAtendimento()->search($this->pesquisa);
        $this->motivosAtendimento = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
