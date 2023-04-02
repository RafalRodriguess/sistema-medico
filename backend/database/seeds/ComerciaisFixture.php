<?php

use App\Comercial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ComerciaisFixture extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $comercial = factory(Comercial::class)
                ->states(
                    Arr::random(['realiza_entrega', 'nao_realiza_entrega']),
                    Arr::random(['retirada_loja', 'sem_retirada_loja']),
                    Arr::random(['exibir']),
                    Arr::random([
                        'aceita_cartao_credito_debito', 'aceita_apenas_cartao_credito',
                        'aceita_apenas_cartao_debito', 'nao_aceita_cartao'
                    ])
                )
                ->create();

            // TODO: Quando adicionar factory produtos, fazer nesta fixture mesmo!
        }
    }
}
