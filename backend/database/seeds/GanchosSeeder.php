<?php

use App\GanchoModel;
use App\Hooks\ChamadasConsultorio;
use App\Hooks\ChamadasGuiche;
use App\Hooks\TriagemIniciada;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GanchosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ganchos = [
            [
                'class' => TriagemIniciada::class,
                'descricao' => 'Senhas chamadas para triagem'
            ],
            [
                'class' => ChamadasGuiche::class,
                'descricao' => 'Senhas chamadas para algum guichê'
            ],
            [
                'class' => ChamadasConsultorio::class,
                'descricao' => 'Senhas chamadas para algum consultório'
            ]
        ];

        foreach ($ganchos as $gancho) {
            if (!DB::table('ganchos')->where('class', $gancho['class'])->exists()) {
                DB::table('ganchos')->insert($gancho);
            } else {
                DB::table('ganchos')->where('class', $gancho['class'])->update($gancho);
            }
        }
    }
}
