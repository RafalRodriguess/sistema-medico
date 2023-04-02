@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Editar Procedimento',
    'breadcrumb' => [
        'Procedimentos' => route('instituicao.procedimentoAtendimentos.index'),
        'Editar',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.procedimentoAtendimentos.update', [$procedimento]) }}" method="post">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-3 form-group @error('convenio_id') has-danger @enderror">
                        <label class="form-control-label">Convênio *</label>
                        <select required id='convenio_id' name="convenio_id"
                            class="form-control select2 convenios @error('convenio_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($convenios as $item)
                                <option value="{{ $item->id }}" @if (old('convenio_id', $procedimento->convenio_id) == $item->id)
                                    selected
                                @endif>
                                    {{ $item->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('convenio_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group @error('plano_id') has-danger @enderror">
                        <label class="form-control-label">Plano</label>
                        <select id='plano_id' name="plano_id"
                            class="form-control select2 planos @error('plano_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($planos as $item)
                                <option value="{{ $item->id }}" @if (old('plano_id', $procedimento->plano_id) == $item->id)
                                    selected
                                @endif>
                                    {{ $item->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('plano_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group @error('tipo_atendimento') has-danger @enderror">
                        <label class="form-control-label">Tipo atendimento</label>
                        <select id='tipo_atendimento' name="tipo_atendimento"
                            class="form-control select2 @error('tipo_atendimento') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            <option value="urgencia" @if (old('tipo_atendimento', $procedimento->tipo_atendimento) == 'urgencia')
                                selected
                            @endif>Urgência/Emergência</option>
                            <option value="ambulatorio" @if (old('tipo_atendimento', $procedimento->tipo_atendimento) == 'ambulatorio')
                                selected
                            @endif>Ambulatório</option>
                            <option value="internacao" @if (old('tipo_atendimento', $procedimento->tipo_atendimento) == 'internacao')
                                selected
                            @endif>Internação</option>
                        </select>
                        @error('tipo_atendimento')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group @error('origem_id') has-danger @enderror">
                        <label class="form-control-label">Origem</label>
                        <select id='origem_id' name="origem_id"
                            class="form-control select2 @error('origem_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($origens as $item)
                                <option value="{{ $item->id }}" @if (old('origem_id', $procedimento->origem_id) == $item->id)
                                    selected
                                @endif>
                                    {{ $item->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('origem_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group @error('unidade_internacao_id') has-danger @enderror">
                        <label class="form-control-label">Unidade de internação</label>
                        <select id='unidade_internacao_id' name="unidade_internacao_id"
                            class="form-control select2 @error('unidade_internacao_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($internacoes as $item)
                                <option value="{{ $item->id }}" @if (old('unidade_internacao_id', $procedimento->unidade_internacao_id) == $item->id)
                                    selected
                                @endif>
                                    {{ $item->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidade_internacao_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 form-group @error("procedimento_id") has-danger @enderror">
                        <label class="form-control-label">Procedimento Origem*</label>
                        <select required name="procedimento_id"
                            class="form-control select2ProcedimentoPesquisa @error("procedimento_id") form-control-danger @enderror">
                            @if (!empty($procedimento->procedimentoOrigem))
                                    <option value="{{$procedimento->procedimentoOrigem->id}}">{{$procedimento->procedimentoOrigem->descricao}}</option>
                                @else
                                    <option value="">Selecione</option>
                            @endif
                        </select>
                        @error("procedimento_id")
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 p-0 m-0">

                        <div class="card col-sm-12">
    
                            <div class="row mb-3">
                                <div class="col-sm-12 border-bottom bg-light p-3">
                                    <label class="form-control-label p-0 m-0">Procedimentos</label>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row procedimento-itens">
            
                                        @php($oldProc = old('proc') ?: [])
                                        @if ($oldProc)
                                            
                                            @for($i = 0, $max = count($oldProc); $i < $max; $i++)
                                                <div class="col-md-12 item-proc">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <a href="javascrit:void(0)" class="small remove-proc">(remover)</a>
                                                        </div>
                                                        <div class="col-md-3 form-group @error("proc.{$i}.grupo_faturamento_id") has-danger @enderror">
                                                            <label class="form-control-label">Grupo faturamento *</label>
                                                            <select required name="proc[{{$i}}][grupo_faturamento_id]"
                                                                class="form-control select2 @error("proc.{$i}.grupo_faturamento_id") form-control-danger @enderror">
                                                                <option value="">Selecione</option>
                                                                @foreach ($grupos_faturamento as $item)
                                                                    <option value="{{ $item->id }}" @if (old("proc.{$i}.grupo_faturamento_id") == $item->id)
                                                                        selected
                                                                    @endif>
                                                                        {{ $item->descricao }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error("proc.{$i}.grupo_faturamento_id")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-3 form-group @error("proc.{$i}.procedimento_cod") has-danger @enderror">
                                                            <label class="form-control-label">Cod</label>
                                                            <input type="number" name="proc[{{$i}}][procedimento_cod]" value="{{old("proc.{$i}.procedimento_cod")}}"
                                                            class="form-control cod_procedimento @error("proc.{$i}.procedimento_cod") form-control-danger @enderror">
                                                            @error("proc.{$i}.procedimento_cod")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3 form-group @error("proc.{$i}.procedimento_id") has-danger @enderror">
                                                            <label class="form-control-label">Procedimento *</label>
                                                            <select required name="proc[{{$i}}][procedimento_id]"
                                                                class="form-control select2ProcedimentoPesquisaOld @error("proc.{$i}.procedimento_id") form-control-danger @enderror">
                                                                <option value="">Selecione</option>
                                                            </select>
                                                            @error("proc.{$i}.procedimento_id")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3 form-group @error("proc.{$i}.quantidade") has-danger @enderror">
                                                            <label class="form-control-label">Quantidade</label>
                                                            <input type="number" name="proc[{{$i}}][quantidade]" value="{{old("proc.{$i}.quantidade")}}"
                                                            class="form-control @error("proc.{$i}.quantidade") form-control-danger @enderror">
                                                            @error("proc.{$i}.quantidade")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else

                                            @for($i = 0, $max = count($procedimento->procedimento); $i < $max; $i++)
                                                <div class="col-md-12 item-proc">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <a href="javascrit:void(0)" class="small remove-proc">(remover)</a>
                                                        </div>
                                                        <div class="col-md-3 form-group @error("proc.{$i}.grupo_faturamento_id") has-danger @enderror">
                                                            <label class="form-control-label">Grupo faturamento *</label>
                                                            <select required name="proc[{{$i}}][grupo_faturamento_id]"
                                                                class="form-control select2 @error("proc.{$i}.grupo_faturamento_id") form-control-danger @enderror">
                                                                <option value="">Selecione</option>
                                                                @foreach ($grupos_faturamento as $item)
                                                                    <option value="{{ $item->id }}" @if (old("proc.{$i}.grupo_faturamento_id", $procedimento->procedimento[$i]->pivot->grupo_faturamento_id) == $item->id)
                                                                        selected
                                                                    @endif>
                                                                        {{ $item->descricao }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error("proc.{$i}.grupo_faturamento_id")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-3 form-group @error("proc.{$i}.procedimento_cod") has-danger @enderror">
                                                            <label class="form-control-label">Cod</label>
                                                            <input type="number" name="proc[{{$i}}][procedimento_cod]" value="{{old("proc.{$i}.procedimento_cod", $procedimento->procedimento[$i]->pivot->procedimento_cod)}}"
                                                            class="form-control cod_procedimento @error("proc.{$i}.procedimento_cod") form-control-danger @enderror">
                                                            @error("proc.{$i}.procedimento_cod")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3 form-group @error("proc.{$i}.procedimento_id") has-danger @enderror">
                                                            <label class="form-control-label">Procedimento *</label>
                                                            <select required name="proc[{{$i}}][procedimento_id]"
                                                                class="form-control select2ProcedimentoPesquisaOld @error("proc.{$i}.procedimento_id") form-control-danger @enderror">
                                                                <option value="{{$procedimento->procedimento[$i]->id}}">{{$procedimento->procedimento[$i]->descricao}}</option>
                                                            </select>
                                                            @error("proc.{$i}.procedimento_id")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3 form-group @error("proc.{$i}.quantidade") has-danger @enderror">
                                                            <label class="form-control-label">Quantidade</label>
                                                            <input type="number" name="proc[{{$i}}][quantidade]" value="{{old("proc.{$i}.quantidade", $procedimento->procedimento[$i]->pivot->quantidade)}}"
                                                            class="form-control @error("proc.{$i}.quantidade") form-control-danger @enderror">
                                                            @error("proc.{$i}.quantidade")
                                                                <div class="form-control-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                        @endif
                                        
            
                                        <div class="form-group col-md-12 add-class" >
                                            <span alt="default" class="add-procedimento fas fa-plus-circle">
                                                <a class="mytooltip" href="javascript:void(0)">
                                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar procedimentos"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
    
                                
    
                            </div>
    
                        </div>
    
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.procedimentoAtendimentos.index') }}">
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
            var quantidade_proc = 0;

            $(document).ready(function(){
                quantidade_proc = $(".item-proc").length;
                $(".select2ProcedimentoPesquisaOld").select2({
                    placeholder: "Pesquise por procedimento",
                    allowClear: true,
                    minimumInputLength: 3,
                    language: {
                    searching: function () {
                        return 'Buscando procedimentos (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                    },

                    ajax: {
                        url:"{{route('instituicao.procedimentosAtendimentos.getProcedimentoGerais')}}",
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

                        // console.log(data.results)
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                        },
                        cache: true
                    },

                }).on('select2:select', function (e) {
                    var procedimento_id = $(this).find("option:selected").val();
                    var element = $(this)
                    if(procedimento_id != ""){
                        $.ajax({
                            type: "POST",
                            data: {'_token': '{{csrf_token()}}'},
                            url: "{{route('instituicao.procedimentoAtendimentos.getCodProcedimento', ['procedimento' => 'procedimento_id'])}}".replace('procedimento_id', procedimento_id),
                            datatype: "json",
                            success: function(result) {
                                if(JSON.stringify(result) != JSON.stringify({})){
                                    element.parents('.item-proc').find('.cod_procedimento').val(result)
                                }
                            }

                        });
                    }
                    
                })

                $(".select2ProcedimentoPesquisa").select2({
                    placeholder: "Pesquise por procedimento",
                    allowClear: true,
                    minimumInputLength: 3,
                    language: {
                    searching: function () {
                        return 'Buscando procedimentos (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                    },

                    ajax: {
                        url:"{{route('instituicao.procedimentosAtendimentos.getProcedimentoGerais')}}",
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

                        // console.log(data.results)
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.descricao}`,
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

            $(".convenios").on('change', function(){
               getPlanos();
            })

            function getPlanos(){
                var convenio_id = $(".convenios option:selected").val();
                var options = $('.planos');
                options.val("").change()
                if(convenio_id != ""){
                    $.ajax({
                        type: "POST",
                        data: {'_token': '{{csrf_token()}}'},
                        url: "{{route('instituicao.procedimentoAtendimentos.getPlanos', ['convenio' => 'convenio_id'])}}".replace('convenio_id', convenio_id),
                        datatype: "json",
                        success: function(result) {
                            
                            if(result != null){
                                planos = result;
                                
                                options.find('option').filter(':not([value=""])').remove();
                                $.each(planos, function (key, value) {
                                            // $('<option').val(value.id).text(value.Nome).appendTo(options);
                                            options.append('<option value='+value.id+'>'+value.nome+'</option>')
                                    //options += '<option value="' + key + '">' + value + '</option>';
                                });
                            }else{
                                
                                options.find('option').filter(':not([value=""])').remove();
                            }
                        }

                    });
                }else{
                    
                    options.find('option').filter(':not([value=""])').remove();
                }
            }

            
            
            $('.procedimento-itens').on('click', '.add-procedimento', function(){
                addProc();
            });

            function addProc(){            
                $($('#item-proc').html()).insertBefore(".add-class");

                $('.mask_item').setMask();
                $('.mask_item').removeClass('mask_item');
                $('.select2new').select2();
                $('.select2new').removeClass('select2new');
                $(".select2ProcedimentoPesquisa").select2({
                    placeholder: "Pesquise por procedimento",
                    allowClear: true,
                    minimumInputLength: 3,
                    language: {
                    searching: function () {
                        return 'Buscando procedimentos (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                    },

                    ajax: {
                        url:"{{route('instituicao.procedimentosAtendimentos.getProcedimentoGerais')}}",
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

                        // console.log(data.results)
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                        },
                        cache: true
                    },

                }).on('select2:select', function (e) {
                    var procedimento_id = $(this).find("option:selected").val();
                    var element = $(this)
                    if(procedimento_id != ""){
                        $.ajax({
                            type: "POST",
                            data: {'_token': '{{csrf_token()}}'},
                            url: "{{route('instituicao.procedimentoAtendimentos.getCodProcedimento', ['procedimento' => 'procedimento_id'])}}".replace('procedimento_id', procedimento_id),
                            datatype: "json",
                            success: function(result) {
                                // console.log(Object.keys(result).length === 0) 
                                if(JSON.stringify(result) != JSON.stringify({})){
                                    element.parents('.item-proc').find('.cod_procedimento').val(result)
                                }
                            }

                        });
                    }
                    
                })
                $('.select2ProcedimentoPesquisa').removeClass('select2ProcedimentoPesquisa');

                $("[name^='proc[#]']").each(function(index, element) {
                    const name = $(element).attr('name');

                    $(element).attr('name', name.replace('#',quantidade_proc));
                })

                quantidade_proc++;
            }

            $('.procedimento-itens').on('click', '.item-proc .remove-proc', function(e){
                e.preventDefault()

                $(e.currentTarget).parents('.item-proc').remove();
                if ($('.procedimento-itens').find('.item-proc').length == 0) {
                    quantidade_proc = 0;
                }
            });
        </script>

        <script type="text/template" id="item-proc">
            <div class="col-md-12 item-proc">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-proc">(remover)</a>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Grupo faturamento *</label>
                        <select required name="proc[#][grupo_faturamento_id]"
                            class="form-control select2new">
                            <option value="">Selecione</option>
                            @foreach ($grupos_faturamento as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->descricao }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label class="form-control-label">Cod</label>
                        <input type="number" name="proc[#][procedimento_cod]"
                        class="form-control cod_procedimento ">
                    </div>

                    <div class="col-md-3 form-group ">
                        <label class="form-control-label">Procedimento *</label>
                        <select required name="proc[#][procedimento_id]"
                            class="form-control select2ProcedimentoPesquisa">
                            <option value="">Selecione</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="form-control-label">Quantidade</label>
                        <input type="number" name="proc[#][quantidade]"
                        class="form-control">
                    </div>
                </div>
            </div>
        </script>
    @endpush
