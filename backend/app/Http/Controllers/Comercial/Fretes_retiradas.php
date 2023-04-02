<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Requests\ComercialFretes\Retirada\CriarEnderecoRetirada;
use App\FretesEntrega;
use App\FretesRetirada;
use App\FretesRetiradaHorario;
use App\Comercial;
use App\Fretes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Fretes_retiradas extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_fretes');

        return view('comercial.fretes.formulario_retiradas.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEnderecoRetirada $request)
    {

        $dados = $request->validated();

        $comercial_id = $request->session()->get('comercial');
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_fretes');

        $frete = Fretes::whereHas('comercial', function ($q) use ($comercial_id) {
            $q->where('tipo_frete', 'retirada');
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();


        $create = [
            'nome' => $dados['nome'],
            'rua' => $dados['rua'],
            'numero' => $dados['numero'],
            'bairro' => $dados['bairro'],
            'cidade' => $dados['cidade'],
            'estado' => $dados['estado'],
            'tipo_prazo_minimo' => $dados['tipo_prazo_minimo'],
            'tipo_prazo_maximo' => $dados['tipo_prazo_maximo'],
            'prazo_minimo' => $dados['prazo_minimo'],
            'prazo_maximo' => $dados['prazo_maximo'],
            'cep' => $dados['cep'],
            'fretes_id' => $frete->id,
        ];

        if ($create && $frete) {

            //caso nao exista, cadastro um novo
            $new = FretesRetirada::create($create);


            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $new->criarLogCadastro(
                $usuario_logado,
                $comercial_id
            );


            $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
            //Verifica se foi inserido os dias e horarios de retirada
            foreach ($dados as $key => $value) {
                if (in_array($key, $dias)) {
                    $horarios[] = [
                        'retirada_id' => $new->id,
                        'dia' => $key,
                        'inicio' => $dados['inicio_' . $key],
                        'fim' => $dados['fim_' . $key],
                    ];
                }
            }
            if (isset($horarios)) {
                //Salva os horarios de retirada
                FretesRetiradaHorario::insert($horarios);
            }


            return redirect()->route('comercial.fretes_retiradas')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Endereço criado com sucesso!'
            ]);
        } else {

            return redirect()->route('comercial.fretes_retiradas')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Falha ao cadastrar o Endereço de retirada!'
            ]);
        }
    }


    public function edit(FretesRetirada $filtro)
    {

        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');

        $horarios['segunda'] = '';
        $horarios['inicio_segunda'] = '';
        $horarios['fim_segunda'] = '';
        $horarios['terca'] = '';
        $horarios['inicio_terca'] = '';
        $horarios['fim_terca'] = '';
        $horarios['quarta'] = '';
        $horarios['inicio_quarta'] = '';
        $horarios['fim_quarta'] = '';
        $horarios['quinta'] = '';
        $horarios['inicio_quinta'] = '';
        $horarios['fim_quinta'] = '';
        $horarios['sexta'] = '';
        $horarios['inicio_sexta'] = '';
        $horarios['fim_sexta'] = '';
        $horarios['sabado'] = '';
        $horarios['inicio_sabado'] = '';
        $horarios['fim_sabado'] = '';
        $horarios['domingo'] = '';
        $horarios['inicio_domingo'] = '';
        $horarios['fim_domingo'] = '';

        foreach ($filtro->horarios as $key => $value) {
            $dia = $value->dia;
            $horarios[$dia] = 1;
            $horarios['inicio_' . $dia] = $value->inicio;
            $horarios['fim_' . $dia] = $value->fim;
        }

        return view('comercial.fretes/formulario_retiradas/editar', \compact('filtro', 'horarios'));
    }


    public function update(CriarEnderecoRetirada $request, FretesRetirada $filtro)
    {



        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');

        $dados = $request->validated();

        DB::transaction(function () use ($request, $filtro, $dados) {
            $filtro->update($dados);

            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $filtro->criarLogEdicao(
                $usuario_logado,
                $comercial_id
            );

            return $filtro;
        });

        $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

        foreach ($dias as $key => $value) {
            //checa se o dia ja está ativado no banco
            $checkDia = FretesRetiradaHorario::where(['retirada_id' => $filtro->id, 'dia' => $value]);

            if ($checkDia->count()) {
                //verifica se o usuario mantem o dia
                if ($request->$value) {
                    //atualiza os horarios
                    $horariosUpdate = array(
                        'inicio' => $request->get('inicio_' . $value),
                        'fim' => $request->get('fim_' . $value),
                    );
                    $checkDia->update($horariosUpdate);
                } else {
                    $checkDia->delete();
                }
            } else {

                //verifica se o usuario adicionou o dia
                if ($request->$value) {

                    //atualiza os horarios
                    $horariosCreate = array(
                        'retirada_id' => $filtro->id,
                        'dia' => $value,
                        'inicio' => $request->get('inicio_' . $value),
                        'fim' => $request->get('fim_' . $value),
                    );
                    $checkDia->create($horariosCreate);
                }
            }
        }

        return redirect()->route('comercial.fretes_retirada.edit', [$filtro])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Endereço atualizado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FretesRetirada $filtro)
    {

        $this->authorize('habilidade_comercial_sessao', 'excluir_fretes');
        DB::transaction(function () use ($request, $filtro) {
            $filtro->delete();

            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $filtro->criarLogExclusao(
                $usuario_logado,
                $comercial_id
            );

            return $filtro;
        });

        return redirect()->route('comercial.fretes_retiradas')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Endereço excluido com sucesso!'
        ]);
    }
}
