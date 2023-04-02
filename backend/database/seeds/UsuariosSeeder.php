<?php

use App\Usuario;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Usuario::create([
    		'nome' => 'Radiografia',
            'data_nascimento' => 'exame',
            'cpf' => '117.513.116-43',
            'telefone' => '(34) 5432-5345',
            'password' => bcrypt('123'),
            'email' => 'lan@gmail.com',
            'customer_id' => '3519273'
    	]);
    }
}
