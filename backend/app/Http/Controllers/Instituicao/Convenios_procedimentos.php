<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;

use App\Convenio;
use App\Instituicao;
use App\Procedimento;
use App\InstituicaoProcedimentos;
use App\ConveniosProcedimentos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use function Clue\StreamFilter\fun;

class Convenios_procedimentos extends Controller
{
	public function index(Request $request)
	{
		$this->authorize('habilidade_instituicao_sessao', 'visualizar_convenios');
		return view('instituicao.convenios_instituicao/lista');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenios');

		$instituicao = Instituicao::find($request->session()->get('instituicao'));

		$convenios = Convenio::whereDoesntHave('procedimentoConvenioInstuicao', function ($q) use ($request) {
			$q->where('instituicoes_id', $request->session()->get('instituicao'));
		})
		->where('instituicao_id', $instituicao->id)
		->get();

		$medicos = $instituicao
			->medicos()
			->whereHas('prestadoresInstituicoes', function ($query) use ($instituicao) {
                $query->where('ativo', 1);
            })
			->orderBy('nome', 'ASC')
			->get();

		return view('instituicao.convenios_instituicao/criar', \compact('convenios', 'medicos'));
	}

	public function store(Request $request)
	{
		$this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenios');
		//procedimento selecionados
		$procedimentos  = $request->input('input_procedimento');
		
		$instituicao = Instituicao::find($request->session()->get('instituicao'));

		$usuario_logado = $request->user('instituicao');

		$convenio  = $request->input('convenio');
		foreach ($procedimentos as $key => $value) {
			// checa se o procedimento está cadastrado na instituicao
			
			$dados = [
				'instituicoes_id' => $instituicao->id,
				'procedimentos_id' =>  $key,
			];

			$procInst = InstituicaoProcedimentos::where($dados)->first()->id;
			
			if ($procInst) {
				$dados = $value;
				// dd($value);
				unset($dados['valor'], $dados['valor_convenio']);
				// dd($request->input('input_procedimento'));
				$repasses = collect($dados)
					->filter(function($repasse){
						return array_key_exists('checkbox', $repasse);
					})
					->map(function($repasse){
						return [
							'prestador_id' => $repasse['checkbox'],
							'tipo' => $repasse['tipo'],
							'tipo_cartao' => $repasse['tipo_cartao'],
							'valor_repasse' => $repasse['valor_repasse'],
							'valor_repasse_cartao' => $repasse['valor_repasse_cartao'],
							'valor_cobrado' => $repasse['valor_cobrado']
						];
					});
				
				//checa se ja nao existe a relação
				$CheckRelation = ConveniosProcedimentos::where('convenios_id', '=', $convenio)
					->where('procedimentos_instituicoes_id', '=', $procInst)->first();
				if (!$CheckRelation) {
					//salva
					$insert = [
						'valor' => $value['valor'],
						'valor_convenio' => $value['valor_convenio'],
						'convenios_id' => $convenio,
						'procedimentos_instituicoes_id' => $procInst,
					];

					$convenioProcedimento = ConveniosProcedimentos::create($insert);

					$convenioProcedimento->criarLogCadastroConvenios(
						$usuario_logado,
						$instituicao->id
					);

					$convenioProcedimento->repasseMedico()->attach($repasses);
				}
			}
		}

		return redirect()->route('instituicao.convenios.index')->with('mensagem', [
			'icon' => 'success',
			'title' => 'Sucesso.',
			'text' => 'Procedimentos e convênios vinculados com sucesso!'
		]);
	}

	public function show()
	{
		# code...
	}

	public function edit(Request $request, Convenio $convenio)
	{



		$this->authorize('habilidade_instituicao_sessao', 'editar_convenios');

		$instituicao = Instituicao::find($request->session()->get('instituicao'));

		//busca todos os procedimentos da instituicao vinculado ao convenio selecionado
		$procedimentos = ConveniosProcedimentos::where('convenios_id', $convenio->id)
			->with('procedimentoInstituicao', 'procedimentoInstituicao.procedimento', 'repasseMedico')
			// ->join('procedimentos_instituicoes', 'procedimentos_instituicoes_convenios.procedimentos_instituicoes_id', '=', 'procedimentos_instituicoes.id')
			// ->join('procedimentos', 'procedimentos_instituicoes.procedimentos_id', '=', 'procedimentos.id')
			// ->where('instituicoes_id', $instituicao->id)
			// ->whereNull('procedimentos.deleted_at')
			// ->whereNull('procedimentos_instituicoes.deleted_at')
			// ->where('instituicoes_id', $instituicao->id)
			->get();

		$dados = [];

		foreach ($procedimentos as $key => $procedimento) {
			if(count($procedimento->repasseMedico) > 0){
				foreach ($procedimento->repasseMedico as $key => $value) {
					$dados[$procedimento->id][$value->id] = [
						$value->id => $value->id,
						'tipo' => $value->pivot->tipo,
						'valor_repasse' => $value->pivot->valor_repasse,
						'valor_cobrado' => $value->pivot->valor_cobrado, 
					];
				}	
			}
		}

		$medicos = $instituicao
			->medicos()
			->whereHas('prestadoresInstituicoes', function ($query) use ($instituicao) {
                $query->where('ativo', 1);
            })
			->orderBy('nome', 'ASC')
			->get();

		// dd($medicos->toArray());


		return view('instituicao.convenios_instituicao/editar', \compact('procedimentos', 'convenio', 'medicos', 'dados', 'instituicao'));
	}


	public function update(Request $request, Convenio $convenio)
	{
		$this->authorize('habilidade_instituicao_sessao', 'editar_convenios');
		$instituicao = Instituicao::find($request->session()->get('instituicao'));
		$usuario_logado = $request->user('instituicao');
		//procedimento selecionados
		$procedimentos  = $request->input('input_procedimento');

		if(!empty($procedimentos)){

			foreach ($procedimentos as $key => $value) {
				// checa se o procedimento está cadastrado na instituicao
				$dados = [
					'instituicoes_id' => $instituicao->id,
					'procedimentos_id' =>  $key,
				];

				// $procInst = InstituicaoProcedimentos::where($dados)->first()->id;
				$procInst = InstituicaoProcedimentos::where($dados)->first();
				
				//checa se ja nao existe a relação
				$CheckRelation = ConveniosProcedimentos::where('convenios_id', '=', $convenio->id)
					->where('procedimentos_instituicoes_id', '=', $procInst['id'])->first();

					// dd($CheckRelation);
					//CASO POSSUA FATURAMENTO SANCOOP IREMOS ATUALIZAR O CÓDIGO DA INSTITUIÇÃO NA SANCOOP
					if($instituicao->possui_faturamento_sancoop == 1 && !$CheckRelation){
						$sancoop_convenio_procedimento = $this->consultarCodConvenioProcedimentosSancoop($convenio->cnpj, $value['codigo']);
					}else if($instituicao->possui_faturamento_sancoop == 1 && $CheckRelation->sancoop_cod_procedimento == null){
						$sancoop_convenio_procedimento =  $this->consultarCodConvenioProcedimentosSancoop($convenio->cnpj, $value['codigo']);
					}else{
						$sancoop_convenio_procedimento = null;
					}

				// se nao existe, cadastra um novo
				if (!$CheckRelation) {
					$dados = $value;
					unset($dados['valor'], $dados['valor_convenio'], $dados['codigo'], $dados['utiliza_parametro_convenio'], $dados['carteirinha_obrigatoria'], $dados['aut_obrigatoria']);
					
					//salva
					$insert = [
						'valor' => $value['valor'],
						'valor_convenio' => $value['valor_convenio'],
						'codigo' => $value['codigo'],
						'convenios_id' => $convenio->id,
						'procedimentos_instituicoes_id' => $procInst['id'],
						'utiliza_parametro_convenio' => (!empty($value['utiliza_parametro_convenio'])) ? 1 : 0,
						'carteirinha_obrigatoria' => (!empty($value['carteirinha_obrigatoria'])) ? 1 : 0,
						'aut_obrigatoria' => (!empty($value['aut_obrigatoria'])) ? 1 : 0,
					];

					if($sancoop_convenio_procedimento != null):
						$insert['sancoop_cod_procedimento'] = ($sancoop_convenio_procedimento == "") ? $sancoop_convenio_procedimento['codigo'] : null;
						$insert['sancoop_desc_procedimento'] = $sancoop_convenio_procedimento['descricao'];
					endif;

					$convenioProcedimento = ConveniosProcedimentos::create($insert);
					//cria log de cadastro
					$convenioProcedimento->criarLogCadastroConvenios($usuario_logado, $instituicao->id);

					$repasses = collect($dados)
					->filter(function($repasse){
						return array_key_exists('checkbox', $repasse);
					})
					->map(function($repasse){
						return [
							'prestador_id' => $repasse['checkbox'],
							'tipo' => $repasse['tipo'],
							'tipo_cartao' => $repasse['tipo_cartao'],
							'valor_repasse' => ($repasse['valor_repasse']) ? $repasse['valor_repasse'] : null,
							'valor_repasse_cartao' => ($repasse['valor_repasse_cartao']) ? $repasse['valor_repasse_cartao'] : null,
							'valor_cobrado' => ($repasse['valor_cobrado']) ? $repasse['valor_cobrado'] : null
						];
					});
					
					$convenioProcedimento->repasseMedico()->attach($repasses);
				} else {
					$dados = $value;
					unset($dados['valor'], $dados['valor_convenio'], $dados['codigo'], $dados['utiliza_parametro_convenio'], $dados['carteirinha_obrigatoria'], $dados['aut_obrigatoria']);
					unset($dados['sexo'], $dados['qtd_maxima'], $dados['tipo_servico'], $dados['tipo_consulta'], $dados['pacote'], $dados['recalcular'], $dados['busca_ativa'], $dados['parto'], $dados['diaria_uti_rn'], $dados['md_mt']);

					// dd($dados);
					$repasses = collect($dados)
						->filter(function($repasse){
							return array_key_exists('checkbox', $repasse);
						})
						->map(function($repasse){
							return [
								'prestador_id' => $repasse['checkbox'],
								'tipo' => $repasse['tipo'],
								'tipo_cartao' => $repasse['tipo_cartao'],
								'valor_repasse' => ($repasse['valor_repasse']) ? $repasse['valor_repasse'] : null,
								'valor_repasse_cartao' => ($repasse['valor_repasse_cartao']) ? $repasse['valor_repasse_cartao'] : null,
								'valor_cobrado' => (!$repasse['valor_cobrado']) ? null : $repasse['valor_cobrado']
							];
						});
					// edita procedimento
					$update = [
						'valor' => $value['valor'],
						'valor_convenio' => $value['valor_convenio'],
						'convenios_id' => $convenio->id,
						'codigo' => $value['codigo'],
						'procedimentos_instituicoes_id' => $procInst['id'],
						'utiliza_parametro_convenio' => (!empty($value['utiliza_parametro_convenio'])) ? 1 : 0,
						'carteirinha_obrigatoria' => (!empty($value['carteirinha_obrigatoria'])) ? 1 : 0,
						'aut_obrigatoria' => (!empty($value['aut_obrigatoria'])) ? 1 : 0,
					];

					if($sancoop_convenio_procedimento != null):
						$update['sancoop_cod_procedimento'] = ($sancoop_convenio_procedimento == "") ? $sancoop_convenio_procedimento['codigo'] : null;
						$update['sancoop_desc_procedimento'] = $sancoop_convenio_procedimento['descricao'];
					endif;

					$convenioProcedimento = $CheckRelation->update($update);
					$CheckRelation->repasseMedico()->detach();
					$CheckRelation->repasseMedico()->attach($repasses);
					//cria log de cadastro
					// $convenioProcedimento->criarLogEdicaoConvenios($usuario_logado);
				}
			}

			return redirect()->route('instituicao.convenios.index')->with('mensagem', [
				'icon' => 'success',
				'title' => 'Sucesso.',
				'text' => 'Procedimentos e convênios vinculados com sucesso!'
			]);
		}else{
			return redirect()->back()->with('message', [
				'icon' => 'error',
				'title' => 'Falha.',
				'text' => 'Preencha os campos para salvar'
			]);
		}
	}



	public function retiraProcedimentoconvenio(Request $request)
	{
		$this->authorize('habilidade_instituicao_sessao', 'excluir_convenios');
		$instituicao = Instituicao::find($request->session()->get('instituicao'));
		$usuario_logado = $request->user('instituicao');

		$busca = [
			'procedimentos_instituicoes_id' => $request->all('procedimento'),
			'convenios_id' => $request->all('convenio')
		];
		
		$retorno = DB::transaction(function () use ($busca, $usuario_logado, $instituicao) {
			$result = ConveniosProcedimentos::where('convenios_id', $busca['convenios_id']['convenio'])->where('procedimentos_instituicoes_id', $busca['procedimentos_instituicoes_id']['procedimento'])->get();

			foreach ($result as $value) {
				$delete = $value->delete();
				$value->criarLogExclusaoConvenios(
					$usuario_logado,
					$instituicao->id
				);

				if ($delete) {
					return $value->procedimentoInstituicao->procedimento->id;
				} else {
					return FALSE;
				}
			}
		});

		if($retorno){
			return response()->json($retorno);
		}
		
		return response()->json(false);
	}

	public function consultarCodConvenioProcedimentosSancoop($cnpj, $codigo)
    {
		
        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MyMkAhQCM';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //TRATANDO CNPJ PARA CONSULTA
            $cnpj_consulta = str_replace('.', '', $cnpj);
            $cnpj_consulta = str_replace('/', '', $cnpj_consulta);
            $cnpj_consulta = str_replace('-', '', $cnpj_consulta);

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Procedimento?CNPJ='.$cnpj_consulta.'&Procedimento='.$codigo.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            // echo '<pre>';
            // print_r($return);
            // exit;

            if(!empty($return['result']->Procedimento)):

                $result_api['codigo'] =    $return['result']->Procedimento[0]->Procedimento->CodProcedimento;
                $result_api['descricao'] = $return['result']->Procedimento[0]->Procedimento->Procedimento;
                return $result_api;

            else:
                return false;
            endif;

        endif;

    }

	public function getProcedimentoPesquisaConvenio(Request $request)
    {
		
        if ($request->ajax())
        {
			$instituicao = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';
			
            $procedimentos = Procedimento::getProcedimentoPesquisaVinculoConvenio($nome, $instituicao)->simplePaginate(100);

            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

}
