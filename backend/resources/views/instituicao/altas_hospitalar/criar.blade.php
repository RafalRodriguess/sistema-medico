@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Realizar Alta Hospitalar',
        'breadcrumb' => [
            'Internação' => route('instituicao.altasHospitalar.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">
            <form action="{{ route('instituicao.altasHospitalar.store') }}" method="post">
                @csrf
                <div class="row paciente">
                    <div class="col-md form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <input type="hidden" name="paciente_id", id="paciente_id" value="{{ old('paciente_id') }}"/>
                        <label class="form-control-label p-0 m-0">Paciente <span class="text-danger">*</span></label>
                        <i class="mdi mdi-magnify modal_pesquia_paciente btn btn-secondary btn-sm"></i>
                        <i class="mdi mdi-eye-outline modal_mostra_paciente btn btn-secondary btn-sm"></i>
                        <i class="fas fa-bed modal_mostra_internacao btn btn-secondary btn-sm" style="display: none;"></i>
                        <input type="text" name="paciente_nome" id="paciente_nome" class="form-control" disabled/>

                        @if($errors->has('paciente_id'))
                            <small class="form-control-feedback">{{ $errors->first('paciente_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 form-group">
                        <label class="form-control-label p-0 m-0">Internação Id</label>
                        <input type="text" readonly name="internacao_id" id="internacao_id" class="form-control" value="{{ old('internacao_id') }}"/>
                        @if($errors->has('internacao_id'))
                            <small class="form-control-feedback">{{ $errors->first('internacao_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="form-control-label p-0 m-0">Data internação</label>
                        <input type="datetime-local" disabled name="data_internacao" id="data_internacao" class="form-control" value=""/>
                        @if($errors->has('data_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('data_internacao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="form-control-label p-0 m-0">Data alta internação</label>
                        <input type="datetime-local" disabled name="alta_internacao" id="alta_internacao" class="form-control" value=""/>
                        @if($errors->has('alta_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('alta_internacao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-control-label p-0 m-0">ultimo médio</label>
                        <input type="text" disabled name="ultimo_medico" id="ultimo_medico" class="form-control" value=""/>
                        @if($errors->has('ultimo_medico'))
                            <small class="form-control-feedback">{{ $errors->first('ultimo_medico') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-control-label p-0 m-0">Acomodação</label>
                        <input type="text" disabled name="acomodadao" id="acomodadao" class="form-control" value="{{ old('acomodadao') }}"/>
                        @if($errors->has('acomodadao'))
                            <small class="form-control-feedback">{{ $errors->first('acomodadao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-control-label p-0 m-0">Unidade</label>
                        <input type="text" disabled name="unidade" id="unidade" class="form-control" value=""/>
                        @if($errors->has('unidade'))
                            <small class="form-control-feedback">{{ $errors->first('unidade') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-control-label p-0 m-0">Leiro</label>
                        <input type="text" disabled name="leito" id="leito" class="form-control" value=""/>
                        @if($errors->has('leito'))
                            <small class="form-control-feedback">{{ $errors->first('leito') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group @if($errors->has('data_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Data alta <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="data_alta" class="form-control" value="{{ old('data_alta') }}"/>
                        @if($errors->has('data_alta'))
                            <small class="form-control-feedback">{{ $errors->first('data_alta') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('motivo_alta_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Motivo de alta <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0 selectfild2" name="motivo_alta_id" id="motivo_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($motivoAlta as $item)
                                <option {{ (old('motivo_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao_motivo_alta }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('motivo_alta_id'))
                            <small class="form-control-feedback">{{ $errors->first('motivo_alta_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('infeccao_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Infecção <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="infeccao_alta" id="infeccao_alta">
                            <option value="0" selected>Não</option>
                            <option value="1" selected>Sim</option>
                        </select>
                        @if($errors->has('infeccao_alta'))
                            <small class="form-control-feedback">{{ $errors->first('infeccao_alta') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group @if($errors->has('declaracao_obito_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Declaração de óbito</label>
                        <input type="text" name="declaracao_obito_alta" class="form-control" value=""/>
                        @if($errors->has('declaracao_obito_alta'))
                            <small class="form-control-feedback">{{ $errors->first('declaracao_obito_alta') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('procedimento_alta_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Procedimento de alta <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0 selectfild2" name="procedimento_alta_id" id="procedimento_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($procedimentos as $item)
                                <option {{ (old('procedimento_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('declaracao_obito_alta'))
                            <small class="form-control-feedback">{{ $errors->first('procedimento_alta_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('especialidade_alta_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Especialidade de alta <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0 selectfild2" name="especialidade_alta_id" id="especialidade_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($especialidades as $item)
                                <option {{ (old('especialidade_alta_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('declaracao_obito_alta'))
                            <small class="form-control-feedback">{{ $errors->first('especialidade_alta_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('obs_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observação</label>
                        <textarea rows='4' class="form-control @if($errors->has('obs_alta')) form-control-danger @endif" name="obs_alta" id="obs_alta">{{ old('obs_alta') }}</textarea>
                        @if($errors->has('obs_alta'))
                            <small class="form-control-feedback">{{ $errors->first('obs_alta') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.altasHospitalar.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>

            <div id="modal_internacao"></div>

            <div id="ver_paciente"></div>
            <div id="ver_internacao"></div>

            <div id="ver_pre_internacao"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            getPaciente($('#paciente_id').val())
        })

        function getPaciente(id){
           if(id != ''){
                $.ajax({
                    url: "{{route('instituicao.altasHospitalar.getPaciente')}}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        paciente_id: id
                    },
                    success: function(retorno){
                        $('#paciente_id').val(retorno.id);
                        $('#paciente_nome').val(retorno.nome+' - '+retorno.cpf);
                        $("#modalPaciente").modal('hide');
                        getAtendimento(retorno.id);
                    }
                })
           }
        }

        function getAtendimento(id){
           if(id != ''){

                $.ajax({
                    url: "{{route('instituicao.altasHospitalar.getAtendimento')}}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        paciente_id: id
                    },
                    success: function(retorno){
                        if(retorno.icon == "error"){
                            $('#internacao_id').val('');
                            $("#data_internacao").val('');
                            $("#ultimo_medico").val('');
                            $("#alta_internacao").val('');
                            $("#acomodadao").val('');
                            $("#unidade").val('');
                            $("#leito").val('');
                            $(".modal_mostra_internacao").css('display', 'none');

                            $.toast({
                                heading: 'Erro',
                                text: retorno.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        }else{
                            $('#internacao_id').val(retorno.internacao_id);
                            $("#data_internacao").val(retorno.data_internacao);
                            $("#ultimo_medico").val(retorno.ultimo_medico);
                            $("#alta_internacao").val(retorno.alta_internacao);
                            $("#acomodadao").val(retorno.acomodadao);
                            $("#unidade").val(retorno.unidade);
                            $("#leito").val(retorno.leito);
                            $(".modal_mostra_internacao").css('display', '');
                        }
                    }
                })
           }
        }

        $('.paciente').on('click', '.modal_mostra_paciente', function(){
            var id = $("#paciente_id").val()

            if(id != ''){

                var url = "{{ route('instituicao.altasHospitalar.verPaciente') }}";
                var data = {
                    '_token': '{{csrf_token()}}',
                    'paciente_id': id
                };
                var modal = 'modalVerPaciente';

                $('#loading').removeClass('loading-off');
                $('#ver_paciente').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                });
            }else{
                $.toast({
                    heading: 'Erro',
                    text: 'Campo paciente não esta preenchido, selecione um paciente para visualizar seus dados!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });
            }

        })

        $('.paciente').on('click', '.modal_pesquia_paciente', function(){
            var url = "{{ route('instituicao.altasHospitalar.pesquisaPaciente') }}";
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalPaciente';

            $('#loading').removeClass('loading-off');
            $('#modal_internacao').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
                $("#cpf").setMask()
            });

        })

        $('.paciente').on('click', '.modal_mostra_internacao', function(){
            var id = $("#internacao_id").val()

            if(id != ''){

                var url = "{{ route('instituicao.altasHospitalar.verInternacao') }}";
                var data = {
                    '_token': '{{csrf_token()}}',
                    'internacao_id': id
                };
                var modal = 'modalVerInternacao';

                $('#loading').removeClass('loading-off');
                $('#ver_internacao').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                });
            }else{
                $.toast({
                    heading: 'Erro',
                    text: 'Campo paciente não esta preenchido, selecione um paciente para visualizar os dados da internacao!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });
            }

        })


        $("#modal_internacao").on('submit', '#formPesquisarPaciente', function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);

            $.ajax({
                url: "{{route('instituicao.altasHospitalar.getPaciente')}}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function (result) {
                    $("#modal_internacao").find("#tabela").html(result)
                },

                error: function (response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        })
    </script>
@endpush
