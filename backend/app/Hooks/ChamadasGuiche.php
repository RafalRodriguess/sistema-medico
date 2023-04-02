<?php

namespace App\Hooks;

use App\ChamadaTotem;
use App\GanchoModel;
use App\SenhaTriagem;
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;

class ChamadasGuiche extends GanchoModel
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
        $dados_gancho = $this->getDados(SenhaTriagem::class, 'guiche');
        $dados = collect($dados_gancho->dados);

        $origem_id = ChamadaTotem::getOrigemId('guiche');
        $registros = ChamadaTotem::where('origem_chamada', $origem_id)
            ->whereRaw('DATEDIFF(?, updated_at) < 1', date('Y-m-d H:i:s'))
            ->orderBy('etapa_completa', 'asc')
            ->orderBy('updated_at', 'asc')
            ->get();

        if ($registros->count() > 0) {
            $primeiro_registro = $registros->first();
            if ($primeiro_registro->etapa_completa == 1) {
                return [
                    'result' => false,
                    'registro' => $registros[$registros->count() -1]
                ];
            } else {
                $exibicao = null;
                $result = true;
                $horario_chamada = $dados->get('horario_chamada') ?? date('Y-m-d H:i:s');

                // Caso o tempo do atual tenha expirado
                if ((new \DateTime())->getTimestamp() - (new \DateTime($horario_chamada))->getTimestamp() > self::TEMPO_DE_ESPERA) {
                    if ($registros->where('id', $dados->get('ultimo_registro', 0))->count() > 0) {
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
                        $exibicao = $primeiro_registro;
                    }
                } else {
                    $exibicao = ChamadaTotem::find($dados->get('ultimo_registro')) ?? $primeiro_registro;
                }

                $this->setDados($dados_gancho, [
                    'ultimo_registro' => !empty($exibicao) ? $exibicao->id : 0,
                    'horario_chamada' => $horario_chamada
                ], 'guiche');

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
