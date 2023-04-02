<?php

use App\MotivoBaixa;
use Illuminate\Database\Seeder;

class ProductionDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductionHabilidadesAdmin::class);
        $this->call(ProductionHabilidadesComercial::class);
        $this->call(ProductionHabilidadesInstituicao::class);
    }
}
