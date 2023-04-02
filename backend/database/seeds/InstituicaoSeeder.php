<?php

use App\Instituicao;
use App\InstituicaoUsuario;
use App\InstituicaoHabilidade;

use Illuminate\Database\Seeder;

class InstituicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Instituicao::count() > 0) {
            return;
        }

        $asasaude1 = Instituicao::create([
            'chave_unica' => 'santacasa',
            'permite_historico' => 1,
            'nome' => 'Santa Casa',
            'tipo' => 1,
            // 'ramo' => 1,
            'cnpj' => '12.432.234/1231-65',
            'razao_social' => 'Cuidar das pessoas'
        ]);

        $asasaude = Instituicao::create([
            'chave_unica' => 'hospitalhu',
            'nome' => 'Hospital H.U',
            'tipo' => 2,
            // 'ramo' => 2,
            'cnpj' => '16.322.234/0001-15',
            'razao_social' => 'Cuidar das pessoas uai'
        ]);

        $usuario1 = InstituicaoUsuario::create([
            'cpf' => '117.513.116-43',
            'nome' => 'Icaro',
            'email' => 'lanzarini.icaro@gmail.com',
            'password' => '123123'
        ]);

        $usuario2 = InstituicaoUsuario::create([
            'cpf' => '796.184.900-92',
            'nome' => 'teste',
            'email' => 'teste@gmail.com',
            'password' => '123'
        ]);

        $usuario3 = InstituicaoUsuario::create([
            'cpf' => '999.999.999-99',
            'nome' => 'Lucas',
            'email' => 'lucas.rodrigues.rdiniz@gmail.com',
            'password' => '123'
        ]);

        $dados = InstituicaoHabilidade::all();

        $usuario1->instituicao()->sync($asasaude->id);
        $usuario2->instituicao()->sync($asasaude->id);
        $usuario3->instituicao()->sync($asasaude1->id);

        $usuario1->instituicaoHabilidades()->sync(
            $dados->keyBy('id')->map(function() use ($asasaude){
                return [
                    'habilitado' => '1',
                    'instituicao_id' => $asasaude->id
                ];
            })
        );

        $usuario2->instituicaoHabilidades()->sync(
            $dados->keyBy('id')->map(function() use ($asasaude){
                return [
                    'habilitado' => '1',
                    'instituicao_id' => $asasaude->id
                ];
            })
        );

        $usuario3->instituicaoHabilidades()->sync(
            $dados->keyBy('id')->map(function() use ($asasaude1){
                return [
                    'habilitado' => '1',
                    'instituicao_id' => $asasaude1->id
                ];
            })
        );


    }
}
