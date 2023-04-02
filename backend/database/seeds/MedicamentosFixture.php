<?php

use App\Medicamento;
use Illuminate\Database\Seeder;

class MedicamentosFixture extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $medicamento = factory(Medicamento::class)
                ->create();
        }
    }
}
