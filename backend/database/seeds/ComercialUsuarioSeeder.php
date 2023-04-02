<?php

use App\Categoria;
use App\Comercial;
use App\ComercialHabilidade;
use App\ComercialUsuario;
use App\Produto;
use App\ProdutoPergunta;
use App\ProdutoPerguntaAlternativa;
use Illuminate\Database\Seeder;

class ComercialUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (ComercialUsuario::count() > 0) {
            return;
        }

        $comercialAdmin = Comercial::create([
            'nome_fantasia' => 'Tectotum',
            'razao_social' => 'TECOTUM LTDA',
            'cnpj' => '09.086.743/0001-09',
            'email' => 'tec@tec.com',
            'telefone' => '38 9 9199 5545',
            'cep' => '39400-351',
            'rua' => 'asd',
            'bairro' => 'dsa',
            'numero' => '1a',
            'cidade' => 'a',
            'estado' => 'MG',
        ]);

        $usuario = ComercialUsuario::create([
            'nome' => 'Gustavo Lima',
            'cpf' => '097.396.716-16',
            'email' => 'gustavohrol67@gmail.com',
            'password' => '123',
        ]);

        $categoria = Categoria::create([
            'comercial_id' => $comercialAdmin->id,
            'nome' => 'Teste',
        ]);

        $usuario->comercial()->sync($comercialAdmin->id);

        $dados = comercialHabilidade::all();

        // $usuario->comercialHabilidades()->sync(
        //     $dados->keyBy('id')->map(function($habilitado) use ($comercialAdmin){
        //         return [
        //             'habilitado' => $habilitado,
        //             'comercial_id' => $comercialAdmin->id
        //         ];
        //     })
        // );

    }
}
