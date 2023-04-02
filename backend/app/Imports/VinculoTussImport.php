<?php

namespace App\Imports;

use App\Instituicao;
use App\VinculoTuss;
use App\VinculoTussTerminologia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class VinculoTussImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $instituicaoId;
    private $terminologiaId;

    public function __construct(Instituicao $instituicao, VinculoTussTerminologia $terminologia)
    {
        $this->instituicaoId = $instituicao->id;
        $this->terminologiaId = $terminologia->id;
    }

    public function model(array $linha)
    {
        $vinculo = [
            'instituicao_id'        => $this->instituicaoId,
            'terminologia_id'       => $this->terminologiaId,
            'cod_terminologia'      => $linha['Terminologia'] ?? null,
            'cod_termo'             => $linha['Código TUSS'] ?? ($linha['Código'] ?? $linha['Código do Termo']),
            'termo'                 => $linha['Termo'] ?? null,
            'forma_envio'           => $linha['Forma de envio'] ?? null,
            'cod_grupo'             => $linha['Código do grupo'] ?? null,
            'descricao_grupo'       => $linha['Descrição do grupo'] ?? ($linha['Grupo'] ?? null),
            'data_vigencia'         => $this->converteData($linha['Data de início de vigência']),
            'data_vigencia_fim'     => $this->converteData($linha['Data de fim de vigência']),
            'data_implantacao_fim'  => $this->converteData($linha['Data de fim de implantação']),
        ];

        $vinculoBase = VinculoTuss::where('terminologia_id', $vinculo['terminologia_id'])
            ->where('cod_termo', $vinculo['cod_termo'])
            ->where('data_vigencia', '<=', $vinculo['data_vigencia'])
            ->first();

        if ($vinculoBase) {
            return null;
        }

        return new VinculoTuss($vinculo);
    }

    /**
     * referencia: https://docs.laravel-excel.com/3.1/imports/chunk-reading.html
     * 
     * @return integer
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * referencia: https://docs.laravel-excel.com/3.1/imports/chunk-reading.html#using-it-together-with-batch-inserts
     *
     * @return integer
     */
    public function batchSize(): int
    {
        return 1000;
    }

    private function converteData($date)
    {
        return $date ? date('Y-m-d', strtotime(str_replace('/', '-', $date))) : null;
    }
}
