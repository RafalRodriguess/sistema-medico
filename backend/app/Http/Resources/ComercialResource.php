<?php

namespace App\Http\Resources;

use App\Comercial;
use App\Fretes;
use App\FretesRetiradaHorario;
use App\HorarioFuncionamentoComercial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Comercial
 */
class ComercialResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome_fantasia ?: $this->razao_social,
            'funcionalidades' => $this->montarFuncionalidades(),
            'categoria' => $this->categoria,
            'endereco' => [
                'cep' => $this->cep,
                'logradouro' => $this->rua,
                'numero' => $this->numero,
                'bairro' => $this->bairro,
                'cidade' => $this->cidade,
                'estado' => $this->estado,
                'complemento' => $this->complemento,
                'referencia' => $this->referencia,
            ],
            'contatos' => [
                'email' => $this->email,
                'telefone' => $this->telefone,
            ],
            'imagens' => [
                'logo' => asset(Storage::cloud()->url($this->logo)),
                'logo_100px' => asset(Storage::cloud()->url($this->logo_100px)),
                'logo_200px' => asset(Storage::cloud()->url($this->logo_200px)),
                'logo_300px' => asset(Storage::cloud()->url($this->logo_300px))
            ],
            'numero_parcelas' => $this->max_parcela,
            'parcela_gratis' => $this->free_parcela,
            'percento_parcela' => $this->valor_parcela,
            'valor_minimo' => $this->valor_minimo,
            'cartao_entrega' => $this->cartao_entrega,
            'dinheiro' => $this->dinheiro,
            'cartao_credito' => $this->cartao_credito,
            'fretes' => $this->getFretes(),
            'horario_funcionamento' => $this->getHorarioFuncionamento(),
        ];
    }

    private function montarFuncionalidades()
    {
        $funcionalidades = [];
        if ($this->realiza_entrega) {
            $funcionalidades[] = 'Realiza Entrega';
        }
        if ($this->retirada_loja) {
            $funcionalidades[] = 'Permite retirada';
        }
        if ($this->pagamento_cartao === "ambos" || $this->pagamento_cartao === "debito") {
            $funcionalidades[] = 'Aceita cartão de débito';
        }
        if ($this->pagamento_cartao === "ambos" || $this->pagamento_cartao === "credito") {
            $funcionalidades[] = 'Aceita cartão de crédito';
        }

        return $funcionalidades;
    }

    private function getFretes()
    {

        $fretes = Fretes::where('comercial_id', $this->id)->get();

        if($fretes){
            foreach ($fretes as $key => $value) {
                if ($fretes[$key]->tipo_frete != 'retirada') {
                    $fretes[$key]['tipo'] = $fretes[$key]->fretesEntrega()->get();
                }else{
                    $fretes[$key]['tipo'] = $fretes[$key]->fretesRetirada()->get();
                    foreach($fretes[$key]['tipo'] as $key_F => $Retirada){
                        $fretes[$key]['tipo'][$key_F]['horarios'] = $fretes[$key]['tipo'][$key_F]->horarios()->get();
                    }
                }
            }
            return $fretes;
        }

        return '';

    }

    private function getHorarioFuncionamento()
    {
        $horarioFuncionamento = HorarioFuncionamentoComercial::where('comercial_id', $this->id)->get();

        return $horarioFuncionamento;
    }
}
