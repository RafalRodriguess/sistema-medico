<?php

use App\VersaoTiss;
use Illuminate\Database\Seeder;

class VersoesTissSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versoes = [
            [
                'versao' => '1.0',
                'diretorio' => '1_0'
            ]
        ];

        foreach ($versoes as $versao) {
            if (VersaoTiss::where($versao)->first()) {
                VersaoTiss::where('versao', $versao['versao'])->update($versao);
            } else {
                VersaoTiss::create($versao);
            }
        }
    }
}
