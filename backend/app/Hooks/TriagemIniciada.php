<?php

namespace App\Hooks;

use App\ChamadaTotem;
use App\GanchoModel;
use App\SenhaTriagem;
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;

class TriagemIniciada extends GanchoModel
{
    protected $MODELO_DADOS = [
        'ultimo_registro' => 0,
        'horario_chamada' => null
    ];

    /**
     * @inheritdoc
     */
    public function handle($model = null)
    {
        $dados_gancho = $this->getDados(SenhaTriagem::class, 'triagem');
        $dados = collect($dados_gancho->dados);

        $origem_id = ChamadaTotem::getOrigemId('triagem');
        $registros = ChamadaTotem::where('origem_chamada', $origem_id)
            ->whereRaw('DATEDIFF(?, updated_at) < 1', date('Y-m-d H:i:s'))
            ->orderBy('etapa_completa', 'asc')
            ->orderBy('updated_at', 'asc')
            ->get();

        $quant_registros = $registros->count();
        if ($quant_registros > 0) {
            $primeiro_registro = $registros->first();
            if ($primeiro_registro->etapa_completa == 1) {
                return [
                    'result' => false,
                    'registro' => $registros[$quant_registros - 1]
                ];
            } else {

                $exibicao = null;
                $result = true;
                $horario_chamada = $dados->get('horario_chamada') ?? date('Y-m-d H:i:s');
                $ultimo_registro = ChamadaTotem::find($dados->get('ultimo_registro'));

                // Caso o tempo do atual de destaque tenha expirado
                if ((new \DateTime())->getTimestamp() - (new \DateTime($horario_chamada))->getTimestamp() > self::TEMPO_DE_ESPERA) {
                    // Caso o ultimo registro não tenha sua etapa completa, pega o proximo
                    if (!empty($ultimo_registro) && $ultimo_registro->etapa_completa == 0) {
                        foreach ($registros as $key => $registro) {
                            if ($registro->id == $dados->get('ultimo_registro', 0)) {
                                if (!empty($registros[$key + 1] ?? null) && $registros[$key + 1]->etapa_completa == 0) {
                                    $exibicao = $registros[$key + 1];
                                    $horario_chamada = date('Y-m-d H:i:s');
                                } else {
                                    $exibicao = $registro;
                                    $result = false;
                                }
                                break;
                            }
                        }
                    } else {
                        // Do contrário pega o primeiro válido
                        $exibicao = $primeiro_registro;
                    }
                } else {
                    // Do contrário continua destacando o ultimo chamado
                    $exibicao = $ultimo_registro ?? $primeiro_registro;
                }

                $this->setDados($dados_gancho, [
                    'ultimo_registro' => !empty($exibicao) ? $exibicao->id : 0,
                    'horario_chamada' => $horario_chamada
                ], 'triagem');

                return [
                    'result' => $result,
                    'registro' => $exibicao
                ];
            }
        } else {
            return false;
        }
    }
}
