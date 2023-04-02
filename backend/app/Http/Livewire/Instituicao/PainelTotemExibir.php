<?php

namespace App\Http\Livewire\Instituicao;

use App\PainelTotem;
use App\SenhaTriagem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class PainelTotemExibir extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    /**
     * @param PainelTotem
     */
    public $painel;

    public function mount($painel)
    {
        $this->painel = $painel;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $secoes_painel = $this->painel->painelHasTipo()->get()->map(function ($item) {
            $tipo_chamada = $item->tipoChamada()->first();

            return [
                'area' => $item,
                'tipo_chamada' => $tipo_chamada,
                'items' => !empty($tipo_chamada->ganchos_id) ? $tipo_chamada->gancho()->autoHandle(SenhaTriagem::class) : []
            ];
        });
        return view('livewire.instituicao.painel-totem-exibir', \compact('secoes_painel'));
    }
}
