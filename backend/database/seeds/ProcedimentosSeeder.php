<?php

use App\Procedimento;
use Illuminate\Database\Seeder;

class ProcedimentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Procedimento::create([
    		'descricao' => 'Radiografia',
    		'tipo' => 'exame',
    	]);
    	Procedimento::create([
    		'descricao' => 'Ultrassonografia',
    		'tipo' => 'exame',
    	]);
    	Procedimento::create([
    		'descricao' => 'Eletrocadiograma',
    		'tipo' => 'exame',
    	]);
    	Procedimento::create([
    		'descricao' => 'Exame de vista',
    		'tipo' => 'exame',
    	]);
    	Procedimento::create([
    		'descricao' => 'Consulta Cardiologica',
    		'tipo' => 'consulta',
    	]);
    	Procedimento::create([
    		'descricao' => 'Consulta Oftalmológica',
    		'tipo' => 'consulta',
    	]);
    }
}
