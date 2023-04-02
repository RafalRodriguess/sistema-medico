<?php

use App\Habilidade;
use App\PerfilUsuario;
use App\Administrador;
use Illuminate\Database\Seeder;

class AdministradoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Administrador::count() > 0) {
            return;
        }

        $perfilAdmin = PerfilUsuario::create([
            'descricao' => 'Administrador',
            'nome_valido' => 'administrador',
        ]);

        $usuario = Administrador::create([
            'nome' => 'Kennedy Rafael',
            'cpf' => '089.147.906-65',
            'email' => 'kennedytectotum@gmail.com',
            'password' => '123',
            'perfis_usuario_id' => $perfilAdmin->id,
        ]);

        $usuario1 = Administrador::create([
            'nome' => 'Gustavo Lima',
            'cpf' => '097.396.716-16',
            'email' => 'gustavohrol67@gmail.com',
            'password' => '123',
            'perfis_usuario_id' => $perfilAdmin->id,
        ]);

        $usuario2 = Administrador::create([
            'nome' => 'Jorge Fernando',
            'cpf' => '702.332.826-29',
            'email' => 'jorgebg2016@gmail.com',
            'password' => '123',
            'perfis_usuario_id' => $perfilAdmin->id,
        ]);


        $usuario3 = Administrador::create([
            'nome' => 'webertib kaic nogueira luiz',
            'cpf' => '098.270.496.82',
            'email' => 'webertonk23@gmail.com',
            'password' => '130g4hfb',
            'perfis_usuario_id' => $perfilAdmin->id,
        ]);

        $usuario4 = Administrador::create([
            'nome' => 'Lucas',
            'cpf' => '000.000.000.00',
            'email' => 'luck@gmail.com',
            'password' => '123',
            'perfis_usuario_id' => $perfilAdmin->id,
        ]);

        $dados = Habilidade::all();

        $usuario->habilidades()->sync($dados->pluck('id'));
        $usuario1->habilidades()->sync($dados->pluck('id'));
        $usuario2->habilidades()->sync($dados->pluck('id'));
        $usuario3->habilidades()->sync($dados->pluck('id'));
        $usuario4->habilidades()->sync($dados->pluck('id'));

    }
}
