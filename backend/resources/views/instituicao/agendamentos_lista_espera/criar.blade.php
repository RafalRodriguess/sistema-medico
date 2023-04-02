@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar lista de espera',
        'breadcrumb' => [
            'Lista de espera' => route('instituicao.agendamentosListaEspera.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.agendamentosListaEspera.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-6 form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <label class="form-control-label">Paciente <span class="text-danger">*</span></label>
                        <select name="paciente_id" id="paciente_id" class="select2agenda form-control @if ($errors->has('paciente_id'))
                            form-control-danger
                        @endif">
                        </select>
                        @if($errors->has('paciente_id'))
                            <div class="form-control-feedback">{{ $errors->first('paciente_id') }}</div>
                        @endif
                    </div>
                    <div class="col-md-4" style="margin-top: 30px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="radio" id="especialidade" name="tipo_espera" class="filled-in" value="1" checked/>
                                <label for="especialidade">Especialidade<label>
                            {{-- </div>
                            <div class="col-md-6 form-group"> --}}
                                <input type="radio" id="prestador" name="tipo_espera" class="filled-in" value="1"/>
                                <label style="margin-left: 10px;" for="prestador">Prestador<label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 especialidade form-group @if($errors->has('especialidade_id')) has-danger @endif">
                        <label class="form-control-label">Especialidades</label>
                        <select name="especialidade_id" id="especialidade_id" class="selectfild2 form-control @if ($errors->has('especialidade_id'))
                            form-control-danger
                        @endif">
                            <option value="">Nenhum</option>
                            @foreach ($especialidades as $item)
                                <option value="{{$item->id}}">{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('especialidade_id'))
                            <div class="form-control-feedback">{{ $errors->first('especialidade_id') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 prestador form-group @if($errors->has('prestador_id')) has-danger @endif" style="display: none ">
                        <label class="form-control-label">Prestadores</label>
                        <select name="prestador_id" id="prestador_id" class="selectfild2 form-control @if ($errors->has('prestador_id'))
                            form-control-danger
                        @endif" style="width: 100%">
                            <option value="">Nenhum</option>
                            @foreach ($prestadores as $item)
                                <option value="{{$item->id}}">{{$item->nome}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('prestador_id'))
                            <div class="form-control-feedback">{{ $errors->first('prestador_id') }}</div>
                        @endif
                    </div>
                    
                    <div class="col-md-6 form-group @if($errors->has('convenio_id')) has-danger @endif">
                        <label class="form-control-label">Convênio</label>
                        <select name="convenio_id" id="convenio_id" class="selectfild2 form-control @if ($errors->has('convenio_id'))
                            form-control-danger
                        @endif" style="width: 100%">
                            <option value="">Nenhum</option>
                            @foreach ($convenios as $item)
                                <option value="{{$item->id}}">{{$item->nome}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('convenio_id'))
                            <div class="form-control-feedback">{{ $errors->first('convenio_id') }}</div>
                        @endif
                    </div>
                    
                    <div class="col-md-12 form-group @if($errors->has('obs')) has-danger @endif">
                        <label class="form-control-label">Obs</label>
                        <textarea name="obs" id="obs" cols="10" rows="5" class="form-control"></textarea>
                    </div>
                    
                </div>

                <div class="form-group text-right">
                         <a href="{{ route('instituicao.agendamentosListaEspera.index') }}">
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
            $(".select2agenda").select2({
                placeholder: "Pesquise por nome ou cpf",
                allowClear: true,
                minimumInputLength: 3,
                // tags: true,
                // createTag: function (params) {
                // var term = $.trim(params.term);

                // return {
                //     id: term,
                //     text: term + ' (Novo Paciente)',
                //     newTag: true
                // }
                // },
                language: {
                searching: function () {
                    return 'Buscando paciente (aguarde antes de selecionar)…';
                },
                
                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
                },
                },    
                
                ajax: {
                    url:"{{route('instituicao.agendamentos.getPacientes')}}",
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
                            text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
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

        $("#especialidade").on('click', function(){
            verificaRadio("especialidade", "prestador")
        })
        
        $("#prestador").on('click', function(){
            verificaRadio("prestador", "especialidade")
        })

        function verificaRadio(campoValida, campoSecundo){
            if($("#"+campoValida).prop('checked')){
                $("."+campoValida).css('display', 'block')
                $("."+campoSecundo).css('display', 'none')
                $("#"+campoSecundo+"_id").val("").change()
            }else{
                $("."+campoValida).css('display', 'none')
                $("#"+campoValida+"_id").val("").change()
                $("."+campoSecundo).css('display', 'block')
            }
        }
    </script>
@endpush
