<?php

namespace App\Console\Commands;

use App\Instituicao;
use App\Agendamentos;
use App\FaturamentoLote;
use App\FaturamentoLoteGuia;
use App\Prestador;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutomacaoAsaplan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automacao:asaplan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // $instituicao = Instituicao::find($instituicao['id']);
        
        //PRIMEIRO PEGAMOS AS INSITUIÇÕES QUE ESTÃO MARCADAS PARA ENVIAR WHATSAP
        $instituicoes = Instituicao::where('integracao_asaplan', 1)
                                   ->get()
                                   ->toArray();

        if(!empty($instituicoes)):

            foreach ($instituicoes as $instituicao):

                //PEGANDO AS FILIAIS ASAPLAN DA INSTITUICAO 
                $this->sincronizaAsaplanFiliais($instituicao);
                
            endforeach;

        endif;
  
        
    }

    function sincronizaAsaplanFiliais($instituicao)
    {

        $filiais = DB::table('instituicoes_asaplan_filiais')
                ->select('*')
                ->where('instituicoes_id', $instituicao['id'])
                ->get();

                // dd($filiais);
                // exit;

                

       if(!empty($filiais)):

            foreach ($filiais as $filial):


                /*  ATUALIZANDO OS TITULARES  */

                $titulares = DB::connection($filial->database)
                  ->table('clientes_titular')
                  ->select('clientes_titular.idcliente_titular', 
                           'clientes_titular.nome', 
                           'clientes_titular.data_nascimento', 
                           'clientes_titular.telefone', 
                           'clientes_titular.situacao', 
                           'clientes_titular.rg', 
                           'clientes_titular.cpf', 
                           'clientes_titular.numero as numero_casa', 
                           'clientes_titular.complemento as complemento_casa', 
                           'clientes_titular.profissao', 
                           'clientes_titular.email', 
                           'ruas.nome as nome_rua', 
                           'ruas.cep as cep_rua', 
                           'bairros.nome as nome_bairro', 
                           'cidades.nome as nome_cidade',
                           'estados.sigla as sigla_estado')
                  ->where('atualizou_dados', 1)
                  ->join('ruas', 'ruas.idrua', '=', 'clientes_titular.idrua','left')
                  ->join('bairros', 'bairros.idbairro', '=', 'clientes_titular.idbairro','left')
                  ->join('cidades', 'cidades.id', '=', 'bairros.cidades_id','left')
                  ->join('estados', 'estados.id', '=', 'cidades.estados_id','left')
                  ->get();

                //   dd($titulares);
                //   exit;

                  

                  if(!empty($titulares)):

                    foreach ($titulares as $titular):

                        if(!empty($titular->data_nascimento) && $titular->data_nascimento != '0000-00-00'):
                            $nascimento_titular = $titular->data_nascimento;
                        else:
                            $nascimento_titular = null;
                        endif;

                        //PRIMEIRO VERIRICAMOS SE EXISTE O TITULAR, CASO EXISTA ATUALIZAR DADOS

                        $paciente = DB::table('pessoas')
                                     ->select('id')
                                     ->where('instituicao_id', $instituicao['id'])
                                     ->where('asaplan_tipo', 1)
                                     ->where('asaplan_chave_plano', $titular->idcliente_titular)
                                     ->where('asaplan_filial', $filial->cod_filial)
                                     ->first();

                                     

                        //se existir vamos atualizar
                        if(!empty($paciente->id)):

                            DB::table('pessoas')
                              ->where('id',  $paciente->id)
                              ->update(array(
                                    'nome' => $titular->nome,
                                    'asaplan_nome_titular' => $titular->nome,
                                    'asaplan_situacao_plano' => $titular->situacao,
                                    'nascimento' => $nascimento_titular,
                                    'telefone1' => $titular->telefone,
                                    'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                    'cpf' => $titular->cpf,
                                    'identidade' => $titular->rg,
                                    'cep' => $titular->cep_rua,
                                    'estado' => $titular->sigla_estado,
                                    'cidade' => $titular->nome_cidade,
                                    'bairro' => $titular->nome_bairro,
                                    'rua' => $titular->nome_rua,
                                    'numero' => $titular->numero_casa,
                                    'complemento' => $titular->complemento_casa,
                                    'profissao' => $titular->profissao,
                                    'email' => $titular->email,
                              ));

                        //se não vamos criar
                        else:

                            $novo_paciente = array(           
                                'personalidade'  => 1,          
                                'tipo'  => 2,
                                'asaplan_tipo' => 1,
                                'asaplan_chave_plano' => $titular->idcliente_titular,
                                'nome' => $titular->nome,
                                'asaplan_nome_titular' => $titular->nome,
                                'asaplan_situacao_plano' => $titular->situacao,
                                'nascimento' => $nascimento_titular,
                                'telefone1' => $titular->telefone,
                                'asaplan_id_titular' => $titular->idcliente_titular,
                                'asaplan_filial' => $filial->cod_filial,
                                'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                'instituicao_id' => $instituicao['id'],
                                'cpf' => $titular->cpf,
                                'identidade' => $titular->rg,
                                'cep' => $titular->cep_rua,
                                'estado' => $titular->sigla_estado,
                                'cidade' => $titular->nome_cidade,
                                'bairro' => $titular->nome_bairro,
                                'rua' => $titular->nome_rua,
                                'numero' => $titular->numero_casa,
                                'complemento' => $titular->complemento_casa,
                                'profissao' => $titular->profissao,
                                'email' => $titular->email,
                            );

                            DB::table('pessoas')->insert($novo_paciente);


                        endif;


                           /* ATUALIZANDO OS BENEFICIÁRIOS */

                            $beneficiarios = DB::connection($filial->database)
                            ->table('beneficiarios')
                            ->select('clientes_titular.idcliente_titular', 
                                    'clientes_titular.nome as nome_titular', 
                                    'clientes_titular.telefone', 
                                    'clientes_titular.situacao', 
                                    'beneficiarios.idbeneficiario', 
                                    'beneficiarios.nome', 
                                    'beneficiarios.cpf', 
                                    'beneficiarios.rg', 
                                    'beneficiarios.profissao', 
                                    'beneficiarios.data_nascimento')
                            ->where('beneficiarios.idcliente_titular', $titular->idcliente_titular)
                            ->where('beneficiarios.orbito','!=', 'True')
                            ->join('clientes_titular', 'clientes_titular.idcliente_titular', '=', 'beneficiarios.idcliente_titular')
                            ->get();

                            if(!empty($beneficiarios)):

                                foreach ($beneficiarios as $beneficiario):

                                    if(!empty($beneficiario->data_nascimento) && $beneficiario->data_nascimento != '0000-00-00'):
                                        $nascimento_beneficiario = $beneficiario->data_nascimento;
                                    else:
                                        $nascimento_beneficiario = null;
                                    endif;

                                    //PRIMEIRO VERIRICAMOS SE EXISTE O TITULAR, CASO EXISTA ATUALIZAR DADOS

                                    $paciente_beneficiario = DB::table('pessoas')
                                                               ->select('id')
                                                               ->where('instituicao_id', $instituicao['id'])
                                                               ->where('asaplan_tipo', 2)
                                                               ->where('asaplan_chave_plano', $beneficiario->idbeneficiario)
                                                               ->where('asaplan_filial', $filial->cod_filial)
                                                               ->first();

                                    //se existir vamos atualizar
                                      if(!empty($paciente_beneficiario->id)):

                                                    DB::table('pessoas')
                                                            ->where('id',  $paciente_beneficiario->id)
                                                            ->update(array(
                                                                    'nome' => $beneficiario->nome,
                                                                    'asaplan_nome_titular' => $beneficiario->nome_titular,
                                                                    'asaplan_situacao_plano' => $beneficiario->situacao,
                                                                    'nascimento' => $nascimento_beneficiario,
                                                                    'telefone1' => $beneficiario->telefone,
                                                                    'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                                                    'cpf' => $beneficiario->cpf,
                                                                    'identidade' => $beneficiario->rg,
                                                                    'profissao' => $beneficiario->profissao,
                                                            ));

                                      else:

                                        $novo_paciente = array(
                                            'personalidade'  => 1,          
                                            'tipo'  => 2,
                                            'asaplan_tipo' => 2,
                                            'asaplan_chave_plano' => $beneficiario->idbeneficiario,
                                            'nome' => $beneficiario->nome,
                                            'asaplan_nome_titular' => $beneficiario->nome_titular,
                                            'asaplan_situacao_plano' => $beneficiario->situacao,
                                            'nascimento' => $nascimento_beneficiario,
                                            'telefone1' => $beneficiario->telefone,
                                            'asaplan_id_titular' => $titular->idcliente_titular,
                                            'asaplan_filial' => $filial->cod_filial,
                                            'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                            'instituicao_id' => $instituicao['id'],
                                            'cpf' => $beneficiario->cpf,
                                            'identidade' => $beneficiario->rg,
                                            'profissao' => $beneficiario->profissao,
                                        );
            
                                        DB::table('pessoas')->insert($novo_paciente);

                                      endif;


                                endforeach;

                            endif;


                            /*AGORA ATUALIZAMOS O BANCO DO PLANO COMO TITULAR IMPORTADO E ATUALIZADO*/

                            DB::connection($filial->database)
                              ->table('clientes_titular')
                              ->where('idcliente_titular',  $titular->idcliente_titular)
                              ->update(array(
                                      'atualizou_dados' => 0,
                            ));




                    endforeach;

                  endif;




                
                
            endforeach;

            echo 'sincronizado';

       endif;

    }


    

    


}
