<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductionDatabaseSeeder::class,
            GanchosSeeder::class,
            AdministradoresSeeder::class,
            ComercialUsuarioSeeder::class,
            InstituicaoSeeder::class,
            MotivosBaixaSeeder::class,
            VersoesTissSeeder::class,
        ]);
    }
}
