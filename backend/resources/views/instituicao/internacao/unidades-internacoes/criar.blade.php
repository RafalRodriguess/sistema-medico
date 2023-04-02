@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar unidade de Internação',
        'breadcrumb' => [
            'Unidade de Internação' => route('instituicao.internacao.unidade-internacao.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.internacao.unidade-internacao.store') }}" method="post">
                @csrf
                <div class="row">
                    <div wire:ignore class="col-md-5 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome da Unidade <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ old('nome') }}" name="nome">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>
                    <div class="col-md-5 form-group @if($errors->has('cc_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="cc_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($centros_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}" @if (old('cc_id')==$centro_custo->id)
                                    selected
                                @endif>{{ $centro_custo->codigo }} {{ $centro_custo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cc_id'))
                            <small class="form-control-feedback">{{ $errors->first('cc_id') }}</small>
                        @endif
                    </div>
                    <div wire:ignore class="col-md-2 form-group @if($errors->has('hospital_dia')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Hospital Dia <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="hospital_dia">
                            <option selected disabled>Selecione</option>
                            <option value="1" @if (old('hospital_dia')=='1')
                                selected
                            @endif>Sim</option>
                            <option value="0" @if (old('hospital_dia')=='2')
                                selected
                            @endif>Não</option>
                        </select>
                        @if($errors->has('hospital_dia'))
                            <small class="form-control-feedback">{{ $errors->first('hospital_dia') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('tipo_unidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo de Unidade <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo_unidade">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos_unidades as $tipo_unidade)
                                <option value="{{ $tipo_unidade }}" @if (old('tipo_unidade')==$tipo_unidade)
                                    selected
                                @endif>{{ App\UnidadeInternacao::getTipoUnidadeTexto($tipo_unidade) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_unidade'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_unidade') }}</small>
                        @endif
                    </div>
                    <div class="col-md-8 form-group @if($errors->has('localizacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Localização <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ old('localizacao') }}" name="localizacao">
                        @if($errors->has('localizacao'))
                            <small class="form-control-feedback">{{ $errors->first('localizacao') }}</small>
                        @endif
                    </div>
                    <div class="col-sm-2 form-check pt-4 pl-3 m-0 mb-3">
                        <input type="checkbox" class="form-check-input p-0 m-0" name="ativo" value="1"
                            @if(old('ativo')=="1")
                                checked
                            @endif id="ativoCheck">
                        <label class="form-check-label" for="ativoCheck">Ativo</label>
                    </div>
                </div>

                <div class="col-sm-12 p-0 m-0 mb-3">
                    <div class="row p-0 m-0 pt-2 pb-2" id="leitos-lista">
                        @if(old('leitos'))
                            @for ($i = 0; $i < count(old('leitos')) ; $i ++)
                                <div class="col-sm-12 p-0 m-0 leito-item mb-2" id="{{ $i }}">
                                    <div class="card p-0 m-0">
                                        <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                                            <div class="col d-flex p-2 m-0">
                                                <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                                                    <span class="text-dark leito-titulo"></span>
                                                    <button
                                                        onclick="document.querySelector('#leitos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement.parentElement)"
                                                        type="button" class="btn btn-danger">
                                                        <i class="ti-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-1 m-0">
                                            <div class="col-sm-6 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.descricao")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                                                    <input type="text" name="leitos[{{$i}}][descricao]" value='{{ old("leitos.{$i}.descricao") }}' class="form-control campo descricao">
                                                    @if($errors->has("leitos.{$i}.descricao"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.descricao") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.tipo")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                                                    <select class="form-control p-0 m-0 campo tipo" name="leitos[{{$i}}][tipo]">
                                                        <option selected disabled>Selecione</option>
                                                        <?php $tipos_leitos = App\UnidadeLeito::getTipos() ?>
                                                        @foreach ($tipos_leitos as $tipo_leito_id)
                                                            <option value="{{ $tipo_leito_id }}" @if (old("leitos.{$i}.tipo")==$tipo_leito_id)
                                                                selected
                                                            @endif>{{ App\UnidadeLeito::getTipoTexto($tipo_leito_id) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("leitos.{$i}.tipo"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.tipo") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.situacao")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Situação <span class="text-danger">*</span></label>
                                                    <select class="form-control p-0 m-0 campo situacao" name="leitos[{{$i}}][situacao]">
                                                        <option selected disabled>Selecione</option>
                                                        <?php $situacoes_leitos = App\UnidadeLeito::getSituacoes() ?>
                                                        @foreach ($situacoes_leitos as $situacao_leito_id)
                                                            <option value="{{ $situacao_leito_id }}" @if (old("leitos.{$i}.situacao")==$situacao_leito_id)
                                                                selected
                                                            @endif>{{ App\UnidadeLeito::getSituacaoTexto($situacao_leito_id) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->get("leitos.{$i}.situacao"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.situacao") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.sala")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Localização Física</label>
                                                    <input type="text" class="form-control campo sala"
                                                        value='{{ old("leitos.{$i}.sala") }}' name="leitos[{{$i}}][sala]">
                                                    @if($errors->has("leitos.{$i}.sala"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.sala") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.caracteristicas")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Caracteristicas</label>
                                                    <select class="form-control p-0 m-0 campo caracteristicas"
                                                        name="leitos[{{$i}}][caracteristicas][]" multiple>
                                                        <?php $caracteristicas_propostas = App\UnidadeLeito::getCaracteristicasPropostas() ?>
                                                        @foreach ($caracteristicas_propostas as $caracteristica)
                                                            <option value="{{ $caracteristica }}"
                                                                @for ($j = 0 ; $j < count(old("leitos.{$i}.caracteristicas")); $j++)
                                                                    @if (old("leitos.{$i}.caracteristicas.{$j}")==$caracteristica)
                                                                        selected
                                                                    @endif
                                                                @endfor
                                                            >{{ $caracteristica }}</option>
                                                        @endforeach
                                                        @for ($j = 0 ; $j < count(old("leitos.{$i}.caracteristicas")); $j++)
                                                            @if (!in_array(old("leitos.{$i}.caracteristicas.{$j}"), $caracteristicas_propostas))
                                                                <option value="{{ old("leitos.{$i}.caracteristicas.{$j}") }}" selected>
                                                                    {{ old("leitos.{$i}.caracteristicas.{$j}") }}
                                                                </option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                    @if($errors->has("leitos.{$i}.caracteristicas"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.caracteristicas") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.acomodacao_id")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Acomodação <span class="text-primary">*</span></label>
                                                    <select class="form-control p-0 m-0 campo acomodacao_id"
                                                        name="leitos[{{$i}}][acomodacao_id]">
                                                        <option selected disabled>Selecione</option>
                                                        @foreach ($acomodacoes as $acomodacao)
                                                            <option value="{{ $acomodacao->id }}" @if (old("leitos.{$i}.acomodacao_id")==$acomodacao->id)
                                                                selected
                                                            @endif>{{ $acomodacao->descricao }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("leitos.{$i}.acomodacao_id"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.acomodacao_id") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.especialidade_id")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Especifico de Especialidade</label>
                                                    <select class="form-control p-0 m-0 campo especialidade_id"
                                                        name="leitos[{{$i}}][especialidade_id]">
                                                        <option selected disabled>Selecione</option>
                                                        <?php $especialidades = App\Especialidade::all() ?>
                                                        @foreach ($especialidades as $especialidade)
                                                            <option value="{{ $especialidade->id }}" @if (old("leitos.{$i}.especialidade_id")==$especialidade->id)
                                                                selected
                                                            @endif>{{ $especialidade->descricao }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("leitos.{$i}.especialidade_id"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.especialidade_id") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.medico_id")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Especifico de Médico</label>
                                                    <select class="form-control p-0 m-0 campo medico_id"
                                                        name="leitos[{{$i}}][medico_id]">
                                                        <option selected disabled>Selecione</option>
                                                        @foreach ($medicos as $medico)
                                                            <option value="{{ $medico->id }}" @if (old("leitos.{$i}.medico_id")==$medico->id)
                                                                selected
                                                            @endif>{{ $medico->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("leitos.{$i}.medico_id"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.medico_id") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-2 form-check pt-4 pl-3 m-0 mb-3">
                                                <input type="checkbox" class="form-check-input p-0 m-0" name="leitos[{{$i}}][leito_virtual]" value="1"
                                                    @if(!empty(old("leitos.{$i}.leito_virtual")))
                                                        checked
                                                    @endif id="leitoVirtualCheck">
                                                <label class="form-check-label" for="leitoVirtualCheck">Leito virtual</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div class="row bg-light d-flex border justify-content-between p-0 m-0">
                        <div class="col-3 p-3 m-0">
                            <span class="title text-dark">Leitos</span>
                        </div>
                        <div class="col-1 d-flex p-2 m-0">
                            <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                                <button type="button" class="btn btn-primary" id="adiciona-leito">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.unidade-internacao.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script type="text/template" id="leito-item">

        <div class="col-sm-12 p-0 m-0 leito-item mb-2">
            <div class="card p-0 m-0">
                <div class="row bg-light border-bottom d-flex justify-content-end p-0 m-0">
                    <div class="col d-flex p-2 m-0">
                        <div class="row col-sm-12 d-flex justify-content-between align-self-center p-0 m-0">
                            <span class="text-dark leito-titulo"></span>
                            <button
                                onclick="document.querySelector('#leitos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement.parentElement)"
                                type="button" class="btn btn-danger">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row p-1 m-0">
                    <div class="col-sm-6 p-2 m-0">
                        <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo descricao">
                            @if($errors->has('descricao'))
                                <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 p-2 m-0">
                        <div class="form-group @if($errors->has('tipo')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                            <select class="form-control p-0 m-0 campo tipo">
                                <option selected disabled>Selecione</option>
                                <?php $tipos_leitos = App\UnidadeLeito::getTipos() ?>
                                @foreach ($tipos_leitos as $tipo_leito_id)
                                    <option value="{{ $tipo_leito_id }}">{{ App\UnidadeLeito::getTipoTexto($tipo_leito_id) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 p-2 m-0">
                        <div class="form-group @if($errors->has('situacao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Situação <span class="text-danger">*</span></label>
                            <select class="form-control p-0 m-0 campo situacao">
                                <option selected disabled>Selecione</option>
                                <?php $situacoes_leitos = App\UnidadeLeito::getSituacoes() ?>
                                @foreach ($situacoes_leitos as $situacao_leito_id)
                                    <option value="{{ $tipo_leito_id }}">{{ App\UnidadeLeito::getSituacaoTexto($situacao_leito_id) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('situacao'))
                                <small class="form-control-feedback">{{ $errors->first('situacao') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 p-2 m-0">
                        <div class="form-group @if($errors->has('sala')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Localização Física</label>
                            <input type="text" class="form-control campo sala">
                            @if($errors->has('sala'))
                                <small class="form-control-feedback">{{ $errors->first('sala') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 p-2 m-0">
                        <div class="form-group @if($errors->has('caracteristicas')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Caracteristicas</label>
                            <select class="form-control p-0 m-0 campo caracteristicas" multiple>
                                <?php $caracteristicas_propostas = App\UnidadeLeito::getCaracteristicasPropostas() ?>
                                @foreach ($caracteristicas_propostas as $caracteristica)
                                    <option value="{{ $caracteristica }}">{{ $caracteristica }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('caracteristicas'))
                                <small class="form-control-feedback">{{ $errors->first('caracteristicas') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group @if($errors->has('acomodacao_id')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Acomodação <span class="text-primary">*</span></label>
                            <select class="form-control p-0 m-0 campo acomodacao_id">
                                <option selected disabled>Selecione</option>
                                <?php $acomodacoes = App\Acomodacao::all() ?>
                                @foreach ($acomodacoes as $acomodacao)
                                    <option value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('acomodacao_id'))
                                <small class="form-control-feedback">{{ $errors->first('acomodacao_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group @if($errors->has('especialidade_id')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Especifico de Especialidade</label>
                            <select class="form-control p-0 m-0 campo especialidade_id">
                                <option selected disabled>Selecione</option>
                                <?php $especialidades = App\Especialidade::all() ?>
                                @foreach ($especialidades as $especialidade)
                                    <option value="{{ $especialidade->id }}">{{ $especialidade->descricao }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('especialidade_id'))
                                <small class="form-control-feedback">{{ $errors->first('especialidade_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 p-2 m-0">
                        <div class="form-group @if($errors->has('medico_id')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Especifico de Médico</label>
                            <select class="form-control p-0 m-0 campo medico_id">
                                <option selected disabled>Selecione</option>
                                @foreach ($medicos as $medico)
                                    <option value="{{ $medico->id }}">{{ $medico->nome }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('medico_id'))
                                <small class="form-control-feedback">{{ $errors->first('medico_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2 form-check pt-4 pl-3 m-0 mb-3">
                        <input type="checkbox" class="form-check-input p-0 m-0 leito_virtual" value="1">
                        <label class="form-check-label">Leito virtual</label>
                    </div>
                </div>
            </div>
        </div>


    </script>

    <script type="text/javascript">
        $(document).ready(function(){

            $(".caracteristicas").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });


            function hasClass(elemento, classe) {
                return (' '+elemento.className+' ').indexOf(' '+classe+' ')>-1;
            }

            const nomes_campos = [
                'descricao',
                'tipo',
                'situacao',
                'sala',
                'caracteristicas',
                'acomodacao_id',
                'especialidade_id',
                'medico_id',
                'leito_virtual'
            ];

            function leitos(){
                $('#adiciona-leito').on('click', function(){
                    let leito = $($('#leito-item').html())[0];
                    let leitos_lista = document.querySelector('#leitos-lista');
                    let indice = leitos_lista.querySelectorAll('.leito-item').length;
                    let campos = leito.querySelectorAll('.campo');
                    campos.forEach((campo)=>{
                        nomes_campos.forEach((nome_campo)=>{
                            let novo_nome = `leitos[${indice}][${nome_campo}]`;
                            if(nome_campo=='caracteristicas') novo_nome = `${novo_nome}[]`
                            if(hasClass(campo, nome_campo)) campo.name = novo_nome;
                        });
                    });
                    leito.id = indice;
                    leito.querySelector('.leito-titulo').textContent = `Leito #${indice}`;
                    leitos_lista.appendChild(leito);
                    $(".caracteristicas").select2({ tags: true, tokenSeparators: [',', ' ']});
                    leito.scrollIntoView();
                    $('input[type="checkbox"]').iCheck({
                        checkboxClass: 'icheckbox_square',
                        radioClass: 'iradio_square',
                    })
                });
            }

            leitos()
        });
    </script>

@endpush
