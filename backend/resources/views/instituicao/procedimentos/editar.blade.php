@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "Editar procedimento #{$cadastro_procedimento->id} {$cadastro_procedimento->descricao}",
    'breadcrumb' => [
        'procedimentos' => route('instituicao.cadastro-procedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.cadastro-procedimentos.update', [$cadastro_procedimento]) }}" method="post">
                @method('put')
                @csrf
                <div class="row">

                    <div class="col-md-2 form-group @if($errors->has('cod')) has-danger @endif">
                        <label class="form-control-label">Cod: *</label>
                        <input type="text" name="cod" value="{{ old('cod', $cadastro_procedimento->cod) }}"
                        class="form-control @if($errors->has('cod')) form-control-danger @endif">
                        @if($errors->has('cod'))
                        <div class="form-control-feedback">{{ $errors->first('cod') }}</div>
                        @endif
                    </div>
                    <div class="col-md-4 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $cadastro_procedimento->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</label>
                        <script>
                            function onTipoExameChanged(select) {
                                if(select.value == 'exame') {
                                    $('#modalidade_exame_select').removeClass('d-none')
                                } else {
                                    $('#modalidade_exame_select').addClass('d-none')
                                    $('[name="modalidade_exame_id"]')[0].value = ''
                                }
                            }
                        </script>
                        <select name="tipo" onchange="onTipoExameChanged(this)" class="form-control
                            @if($errors->has('tipo')) form-control-danger @endif
                            " id="">
                            <option value="consulta" @if (empty(old('tipo')) && $cadastro_procedimento->tipo == 'consulta' || old('tipo') == 'consulta') selected="selected" @endif>Consulta</option>
                            <option value="exame" @if (empty(old('tipo')) && $cadastro_procedimento->tipo == 'exame' || old('tipo') == 'exame') selected="selected" @endif>Exame</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3" style="margin-top: 33px;">
                        <input type="checkbox" id="odontologico" name="odontologico" class="filled-in" @if ($cadastro_procedimento->odontologico == 1)
                        checked
                        @endif value="1"/>
                        <label for="odontologico">Odontológico<label>
                    </div>
                    <div class="form-group col-md-2 possui_regiao" style="display: none">
                        <input type="checkbox" id="possui_regiao" name="possui_regiao" class="filled-in" @if ($cadastro_procedimento->possui_regiao == 1)
                        checked
                        @endif value="1"/>
                        <label for="possui_regiao">Possui região<label>
                    </div>
                    <div class="form-group col-md-2 tipo_limpeza" style="display: none">
                        <input type="checkbox" id="tipo_limpeza" name="tipo_limpeza" class="filled-in" @if ($cadastro_procedimento->tipo_limpeza == 1)
                        checked
                        @endif value="1"/>
                        <label for="tipo_limpeza">Regiões do tipo limpeza<label>
                    </div>
                    <div class="form-group col-md-2">
                        <input type="checkbox" id="exige_quantidade" name="exige_quantidade" class="filled-in" @if ($cadastro_procedimento->exige_quantidade == 1)
                        checked
                        @endif value="1"/>
                        <label for="exige_quantidade">Exige quantidade<label>
                    </div>
                    <div class="form-group col-md-2">
                        <input type="checkbox" id="n_cobrar_agendamento" name="n_cobrar_agendamento" class="filled-in" @if ($cadastro_procedimento->n_cobrar_agendamento == 1)
                        checked
                        @endif value="1"/>
                        <label for="n_cobrar_agendamento">Não cobrar no agendamento<label>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="row">
                            <div class=" col-md-6 form-group @if($errors->has('valor_custo')) has-danger @endif">
                                <label class="form-control-label">Valor de custo: *</span></label>
                                <input type="text" alt='decimal' name="valor_custo" value="{{ old('valor_custo', $cadastro_procedimento->valor_custo) }}"
                                class="form-control @if($errors->has('valor_custo')) form-control-danger @endif">
                                @if($errors->has('valor_custo'))
                                <div class="form-control-feedback">{{ $errors->first('valor_custo') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 form-group @if($errors->has('duracao_atendimento')) has-danger @endif">
                                <label class="form-control-label">Duração atendimento: </span></label>
                                <input type="number" name="duracao_atendimento" value="{{ old('duracao_atendimento', $cadastro_procedimento->duracao_atendimento) }}"
                                class="form-control @if($errors->has('duracao_atendimento')) form-control-danger @endif">
                                @if($errors->has('duracao_atendimento'))
                                <div class="form-control-feedback">{{ $errors->first('duracao_atendimento') }}</div>
                                @endif
                                <small>**obs: insira o total em minutos</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-md-3 form-group @if($errors->has('tipo_guia')) has-danger @endif">
                        <label class="form-control-label">Tipo de guia: *</label>
                        <select name="tipo_guia" class="form-control @if($errors->has('tipo_guia')) form-control-danger @endif">
                            <option value="0" @if (old('tipo_guia') == '0') selected="selected" @endif>Selecione</option>
                            <option value="1" @if (old('tipo_guia', $cadastro_procedimento->tipo_guia ) == '1') selected="selected" @endif>Consulta</option>
                            <option value="2" @if (old('tipo_guia', $cadastro_procedimento->tipo_guia ) == '2') selected="selected" @endif>SADT</option>
                        </select>
                    </div>
                    <div class=" col-md-3 form-group @if($errors->has('compromisso_id')) has-danger @endif">
                        <label class="form-control-label">Etiqueta: *</label>
                        <select name="compromisso_id" class="form-control select2 @if($errors->has('compromisso_id')) form-control-danger @endif">
                            <option value="">Nenhuma</option>
                            @foreach ($compromissos as $item)
                                <option value="{{$item->id}}" @if (old('compromisso_id', $cadastro_procedimento->compromisso_id) == $item->id) selected="selected" @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                    </div>  
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Sexo</label>
                        <select class="form-control" name="sexo">
                            <option value="ambos" @if (old('sexo', $cadastro_procedimento->sexo) == 'ambos')
                                selected
                            @endif>Ambos</option>
                            <option value="masculino" @if (old('sexo', $cadastro_procedimento->sexo) == 'masculino')
                                selected
                            @endif>Masculino</option>
                            <option value="feminino" @if (old('sexo', $cadastro_procedimento->sexo) == 'feminino')
                                selected
                            @endif>Feminino</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Quantidade</label>
                        <input type="number" class="form-control" name="qtd_maxima" value="{{old('qtd_maxima', $cadastro_procedimento->qtd_maxima)}}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Tipo de serviço hospitalar</label>
                        <select class="form-control" name="tipo_servico">
                            <option value="">Nenhum</option>
                            @foreach ($servicosHospitalares as $item)
                                <option value="{{$item}}" @if (old('tipo_servico', $cadastro_procedimento->tipo_servico) == $item)
                                    selected
                                @endif>{{App\Procedimento::getServicoHospitalaresTexto($item)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Tipo de consulta</label>
                        <select class="form-control" name="tipo_consulta">
                            <option value=""></option>
                            <option value="eletiva" @if (old('tipo_consulta', $cadastro_procedimento->tipo_consulta) == 'eletiva')
                                    selected
                                @endif>Eletiva</option>
                            <option value="urgencia" @if (old('tipo_consulta', $cadastro_procedimento->tipo_consulta) == 'urgencia')
                                    selected
                                @endif>Urgência</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="pacote" name="pacote" value="1" class="filled-in chk-col-teal" @if (old('pacote', $cadastro_procedimento->pacote) == 1)
                                    checked
                                @endif/>
                        <label for="pacote">Pacote</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="recalcular" name="recalcular" value="1" class="filled-in chk-col-teal" @if (old('recalcular', $cadastro_procedimento->recalcular) == 1)
                                    checked
                                @endif/>
                        <label for="recalcular">Recalcula</label>                    
                    </div>
                    
                    <div class="col-md-3">
                        <input type="checkbox" id="busca_ativa" name="busca_ativa" value="1" class="filled-in chk-col-teal" @if (old('busca_ativa', $cadastro_procedimento->busca_ativa) == 1)
                                    checked
                                @endif/>
                        <label for="busca_ativa">Busca ativa</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="parto" name="parto" value="1" class="filled-in chk-col-teal" @if (old('parto', $cadastro_procedimento->parto) == 1)
                                    checked
                                @endif/>
                        <label for="parto">Parto</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="diaria_uti_rn" name="diaria_uti_rn" value="1" class="filled-in chk-col-teal" @if (old('diaria_uti_rn', $cadastro_procedimento->diaria_uti_rn) == 1)
                                    checked
                                @endif/>
                        <label for="diaria_uti_rn">Diária de UTI RN</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="md_mt" name="md_mt" value="1" class="filled-in chk-col-teal" @if (old('md_mt', $cadastro_procedimento->md_mt) == 1)
                                    checked
                                @endif/>
                        <label for="md_mt">MD ou MT é OPME</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="pesquisa_satisfacao" name="pesquisa_satisfacao" value="1" class="filled-in chk-col-teal" @if (old('pesquisa_satisfacao', $cadastro_procedimento->pesquisa_satisfacao) == 1)
                                    checked
                                @endif/>
                        <label for="pesquisa_satisfacao">Não enviar pesquisa de satisfação</label>                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="vinculo_tuss_id" class="control-label">Vinculo tuss:</label>
                        <select class="form-control select2tuss" name="vinculo_tuss_id" id="vinculo_tuss_id" style="width: 100%">
                            @if (!empty($cadastro_procedimento->vinculoTuss))
                                <option value="{{$cadastro_procedimento->vinculoTuss->id}}">{{$cadastro_procedimento->vinculoTuss->termo}} ({{$cadastro_procedimento->vinculoTuss->cod_termo}})</option>
                            @else
                                <option value=""></option>
                            @endif
                        </select>
                        @if($errors->has('vinculo_tuss_id'))
                        <div class="form-control-feedback">{{ $errors->first('vinculo_tuss_id') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-groupn text-right">
                    <a href="{{ route('instituicao.cadastro-procedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            tipoOdonto();
            tipoRegiao();
            $(".select2tuss").select2({
                placeholder: "Pesquise por cod ou termo",
                allowClear: true,
                minimumInputLength: 3,
                language: {
                searching: function () {
                    return 'Buscando vinculo tuss (aguarde antes de selecionar)…';
                },
                
                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
                },
                },    
                
                ajax: {
                    url:"{{route('instituicao.vinculoTuss.getVinculoTuss')}}",
                    dataType: 'json',
                    delay: 100,

                    data: function (params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1
                    };
                    },
                    processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.termo} - (${item.cod_termo})`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                    },
                    cache: true
                },

            })
        })

        $("#odontologico").on('click', function(){
            tipoOdonto();
        })
        $("#possui_regiao").on('click', function(){
            tipoRegiao();
        })

        function tipoOdonto(){
            if($("#odontologico").is(':checked')){
                $(".possui_regiao").css('display', 'block');
            }else{
                $(".possui_regiao").css('display', 'none');
            }
        }
        function tipoRegiao(){
            if($("#possui_regiao").is(':checked')){
                $(".tipo_limpeza").css('display', 'block');
            }else{
                $(".tipo_limpeza").css('display', 'none');
            }
        }
    </script>
@endpush
