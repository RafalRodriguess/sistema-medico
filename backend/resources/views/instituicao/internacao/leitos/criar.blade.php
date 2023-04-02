



@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Leitos',
        'breadcrumb' => [
            'Leitos' => route('instituicao.internacao.leitos.index', [$unidade_internacao]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.leitos.store', [$unidade_internacao]) }}" method="post">
                @csrf
                @if ($errors->has('leitos'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first("leitos") }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

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
                                            <div class="col-sm-4 p-2 m-0">
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
                                            <div class="col-sm-2 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.quantidade")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Quantidade <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control campo quantidade"
                                                        value='{{ old("leitos.{$i}.quantidade") }}' name="leitos[{{$i}}][quantidade]">
                                                    @if($errors->has("leitos.{$i}.quantidade"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.quantidade") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.sala")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Localização Física <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control campo sala"
                                                        value='{{ old("leitos.{$i}.sala") }}' name="leitos[{{$i}}][sala]">
                                                    @if($errors->has("leitos.{$i}.sala"))
                                                        <small class="form-control-feedback">{{ $errors->first("leitos.{$i}.quantidade") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6 p-2 m-0">
                                                <div class="form-group @if($errors->has("leitos.{$i}.caracteristicas")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Caracteristicas <span class="text-primary">*</span></label>
                                                    <select class="form-control p-0 m-0 campo caracteristicas"
                                                        name="leitos[{{$i}}][caracteristicas][]" multiple>
                                                        <?php $caracteristicas_propostas = App\UnidadeLeito::getCaracteristicasPropostas() ?>
                                                        @foreach ($caracteristicas_propostas as $caracteristica)
                                                            <option value="{{ $caracteristica }}"
                                                                @if (old("leitos.{$i}.caracteristicas"))
                                                                    @for ($j = 0 ; $j < count(old("leitos.{$i}.caracteristicas")); $j++)
                                                                        @if (old("leitos.{$i}.caracteristicas.{$j}")==$caracteristica)
                                                                            selected
                                                                        @endif
                                                                    @endfor
                                                                @endif>{{ $caracteristica }}</option>
                                                        @endforeach
                                                        @if (old("leitos.{$i}.caracteristicas"))
                                                            @for ($j = 0 ; $j < count(old("leitos.{$i}.caracteristicas")); $j++)
                                                                @if (!in_array(old("leitos.{$i}.caracteristicas.{$j}"), $caracteristicas_propostas))
                                                                    <option value="{{ old("leitos.{$i}.caracteristicas.{$j}") }}" selected>
                                                                        {{ old("leitos.{$i}.caracteristicas.{$j}") }}
                                                                    </option>
                                                                @endif
                                                            @endfor
                                                        @endif
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
                                                    <label class="form-control-label p-0 m-0">Especifico de Especialidade <span class="text-primary">*</span></label>
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
                                                    <label class="form-control-label p-0 m-0">Especifico de Médico <span class="text-primary">*</span></label>
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
                    <a href="{{ route('instituicao.internacao.leitos.index', [$unidade_internacao]) }}">
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
                    <div class="col-sm-4 p-2 m-0">
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
                    <div class="col-sm-2 p-2 m-0">
                        <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Quantidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo quantidade">
                            @if($errors->has('quantidade'))
                                <small class="form-control-feedback">{{ $errors->first('quantidade') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 p-2 m-0">
                        <div class="form-group @if($errors->has('sala')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Localização Física <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo sala">
                            @if($errors->has('sala'))
                                <small class="form-control-feedback">{{ $errors->first('sala') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 p-2 m-0">
                        <div class="form-group @if($errors->has('caracteristicas')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Caracteristicas <span class="text-primary">*</span></label>
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
                            <label class="form-control-label p-0 m-0">Especifico de Especialidade <span class="text-primary">*</span></label>
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
                            <label class="form-control-label p-0 m-0">Especifico de Médico <span class="text-primary">*</span></label>
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
                'quantidade',
                'sala',
                'caracteristicas',
                'acomodacao_id',
                'especialidade_id',
                'medico_id'

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
                    $('.alert').alert()
                });
            }

            leitos()



        });
    </script>
@endpush
