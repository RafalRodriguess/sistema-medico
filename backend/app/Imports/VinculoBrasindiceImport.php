<?php

namespace App\Imports;

use App\VinculoBrasindice;
use App\VinculoBrasindiceImportacao;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class VinculoBrasindiceImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $importacao;

    public function __construct(VinculoBrasindiceImportacao $importacao)
    {
        $this->importacao = $importacao;
    }

    public function model(array $vinculo)
    {
        $vinculoBase = VinculoBrasindice::where('tiss', $vinculo['tiss'])
            ->where('tipo_preco', $vinculo['tipo_preco'])
            ->first();

        if ($vinculoBase) {
            $vinculoBase->update($vinculo);

            $this->importacao->atualizacoes++;
            $this->importacao->save();

            return null;
        } else {
            $this->importacao->insercoes++;
            $this->importacao->save();

            return new VinculoBrasindice($vinculo);
        }
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
}
