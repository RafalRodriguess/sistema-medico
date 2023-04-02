<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\SetorExame;
use Illuminate\Http\Request;

class SetoresExamePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $setores;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }


    public function render()
    {
        $this->performQuery();

        return view('livewire.instituicao.setores-exame-pesquisa', [
            'setores' => $this->setores
        ]);
    }

    private function performQuery() {
        // Buscando somente os registros das instituições cujo usuario está inserido
        $usuario = request()->user('instituicao');
        // $this->setores = SetorExame::query()->search($this->pesquisa)
        //                                     ->whereIn('instituicao_id', $this->instituicao->id)
        //                                     ->whereNull('deleted_at')
        //                                     ->paginate(15);

        $this->setores = $this->instituicao->setoresExame()->search($this->pesquisa)->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
