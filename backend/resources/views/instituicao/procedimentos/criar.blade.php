@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar Procedimento',
    'breadcrumb' => [
        'Procedimentos' => route('instituicao.cadastro-procedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.cadastro-procedimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-3 form-group @error('grupo_id') has-danger @enderror">
                        <label class="form-control-label">Grupo *</label>
                        <select required id='grupo' name="grupo_id"
                            class="form-control @error('grupo_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">
                                    {{ $grupo->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class=" col-md-2 form-group @if($errors->has('cod')) has-danger @endif">
                        <label class="form-control-label">Cod: </span></label>
                        <input type="text" name="cod" value="{{ old('cod') }}"
                        class="form-control @if($errors->has('cod')) form-control-danger @endif">
                        @if($errors->has('cod'))
                        <div class="form-control-feedback">{{ $errors->first('cod') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-4 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>


                    <div class=" col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</label>
                        <select name="tipo" class="form-control
                        @if($errors->has('tipo')) form-control-danger @endif
                        " id="">
                            <option value="consulta" @if (old('tipo') == 'consulta') selected="selected" @endif>Consulta</option>
                            <option value="exame" @if (old('tipo') == 'exame') selected="selected" @endif>Exame</option>
                        </select>
                        @if($errors->has('tipo'))
                        <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <input type="checkbox" id="odontologico" name="odontologico" class="filled-in" value="1"/>
                        <label for="odontologico">Odontológico<label>
                    </div>
                    <div class="form-group col-md-2 possui_regiao" style="display: none">
                        <input type="checkbox" id="possui_regiao" name="possui_regiao" class="filled-in" value="1"/>
                        <label for="possui_regiao">Possui região<label>
                    </div>
                    <div class="form-group col-md-2 tipo_limpeza" style="display: none">
                        <input type="checkbox" id="tipo_limpeza" name="tipo_limpeza" class="filled-in" value="1"/>
                        <label for="tipo_limpeza">Regiões do tipo limpeza<label>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <input type="checkbox" id="exige_quantidade" name="exige_quantidade" class="filled-in" value="1"/>
                        <label for="exige_quantidade">Exige quantidade<label>
                    </div>

                    <div class="form-group col-md-2">
                        <input type="checkbox" id="n_cobrar_agendamento" name="n_cobrar_agendamento" class="filled-in" value="1"/>
                        <label for="n_cobrar_agendamento">Não cobrar no agendamento<label>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-6 form-group @if($errors->has('valor_custo')) has-danger @endif">
                                <label class="form-control-label">Valor de custo: *</span></label>
                                <input type="text" alt='decimal' name="valor_custo" value="{{ old('valor_custo') }}"
                                class="form-control @if($errors->has('valor_custo')) form-control-danger @endif">
                                @if($errors->has('valor_custo'))
                                <div class="form-control-feedback">{{ $errors->first('valor_custo') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 form-group @if($errors->has('duracao_atendimento')) has-danger @endif">
                                <label class="form-control-label">Duração atendimento: </span></label>
                                <input type="number" name="duracao_atendimento" value="{{ old('duracao_atendimento') }}"
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
                            <option value="1" @if (old('tipo_guia') == '1') selected="selected" @endif>Consulta</option>
                            <option value="2" @if (old('tipo_guia') == '2') selected="selected" @endif>SADT</option>
                        </select>
                    </div>                        
                    <div class=" col-md-3 form-group @if($errors->has('compromisso_id')) has-danger @endif">
                        <label class="form-control-label">Etiqueta: *</label>
                        <select name="compromisso_id" class="form-control select2 @if($errors->has('compromisso_id')) form-control-danger @endif">
                            <option value="">Nenhuma</option>
                            @foreach ($compromissos as $item)
                                <option value="{{$item->id}}" @if (old('compromisso_id') == $item->id) selected="selected" @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                    </div>                        
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Sexo</label>
                        <select class="form-control" name="sexo">
                            <option value="ambos" @if (old('sexo') == 'ambos')
                                selected
                            @endif>Ambos</option>
                            <option value="masculino" @if (old('sexo') == 'masculino')
                                selected
                            @endif>Masculino</option>
                            <option value="feminino" @if (old('sexo') == 'feminino')
                                selected
                            @endif>Feminino</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Quantidade</label>
                        <input type="number" class="form-control" name="qtd_maxima" value="{{old('qtd_maxima')}}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Tipo de serviço hospitalar</label>
                        <select class="form-control" name="tipo_servico">
                            <option value="">Nenhum</option>
                            @foreach ($servicosHospitalares as $item)
                                <option value="{{$item}}" @if (old('tipo_servico') == $item)
                                    selected
                                @endif>{{App\Procedimento::getServicoHospitalaresTexto($item)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Tipo de consulta</label>
                        <select class="form-control" name="tipo_consulta">
                            <option value=""></option>
                            <option value="eletiva" @if (old('tipo_consulta') == 'eletiva')
                                    selected
                                @endif>Eletiva</option>
                            <option value="urgencia" @if (old('tipo_consulta') == 'urgencia')
                                    selected
                                @endif>Urgência</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="pacote" name="pacote" value="1" class="filled-in chk-col-teal" @if (old('pacote'))
                                    checked
                                @endif/>
                        <label for="pacote">Pacote</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="recalcular" name="recalcular" value="1" class="filled-in chk-col-teal" @if (old('recalcular'))
                                    checked
                                @endif/>
                        <label for="recalcular">Recalcula</label>                    
                    </div>
                    
                    <div class="col-md-3">
                        <input type="checkbox" id="busca_ativa" name="busca_ativa" value="1" class="filled-in chk-col-teal" @if (old('busca_ativa'))
                                    checked
                                @endif/>
                        <label for="busca_ativa">Busca ativa</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="parto" name="parto" value="1" class="filled-in chk-col-teal" @if (old('parto'))
                                    checked
                                @endif/>
                        <label for="parto">Parto</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="diaria_uti_rn" name="diaria_uti_rn" value="1" class="filled-in chk-col-teal" @if (old('diaria_uti_rn'))
                                    checked
                                @endif/>
                        <label for="diaria_uti_rn">Diária de UTI RN</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="md_mt" name="md_mt" value="1" class="filled-in chk-col-teal" @if (old('md_mt'))
                                    checked
                                @endif/>
                        <label for="md_mt">MD ou MT é OPME</label>                    
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" id="pesquisa_satisfacao" name="pesquisa_satisfacao" value="1" class="filled-in chk-col-teal" @if (old('pesquisa_satisfacao') == 1)
                                    checked
                                @endif/>
                        <label for="pesquisa_satisfacao">Não enviar pesquisa de satisfação</label>                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="vinculo_tuss_id" class="control-label">Vinculo tuss:</label>
                        <select class="form-control select2tuss" name="vinculo_tuss_id" id="vinculo_tuss_id" style="width: 100%">
                          <option value=""></option>
                        </select>
                        @if($errors->has('vinculo_tuss_id'))
                        <div class="form-control-feedback">{{ $errors->first('vinculo_tuss_id') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
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
            $("#odontologico").on('click', function(){
                if($("#odontologico").is(':checked')){
                    $(".possui_regiao").css('display', 'block');
                }else{
                    $(".possui_regiao").css('display', 'none');
                }
            })
            
            $("#possui_regiao").on('click', function(){
                if($("#possui_regiao").is(':checked')){
                    $(".tipo_limpeza").css('display', 'block');
                }else{
                    $(".tipo_limpeza").css('display', 'none');
                }
            })
            $(document).ready(function(){

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
        </script>
    @endpush
