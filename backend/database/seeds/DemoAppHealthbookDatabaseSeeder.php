<?php

use App\Administrador;
use App\Habilidade;
use App\PerfilUsuario;
use Illuminate\Database\Seeder;

class DemoAppHealthbookDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductionDatabaseSeeder::class);

        $this->registrarUsuarioKennedy();
    }

    private function registrarUsuarioKennedy()
    {
        $perfilAdmin = PerfilUsuario::query()->firstOrCreate([
            'nome_valido' => 'administrador',
        ], [
            'descricao' => 'Administrador',
        ]);

        $usuarioKennedy = Administrador::query()->firstOrCreate([
            'cpf' => '089.147.906-65',
        ], [
            'nome' => 'Kennedy Rafael',
            'email' => 'kennedytectotum@gmail.com',
            'password' => '123',
            'perfis_usuario_id' => $perfilAdmin->id,
            'developer' => true,
        ]);

        $habilidades = Habilidade::all();
        $usuarioKennedy->habilidades()->sync($habilidades->pluck('id'));

        return $usuarioKennedy;
    }
}
