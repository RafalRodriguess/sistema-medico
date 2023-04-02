@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Contas Ambulatorial',
        'breadcrumb' => [
            'Contas Ambulatorial' => route('instituicao.contasAmbulatorial.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <form action="{{ route('instituicao.contasAmbulatorial.store') }}" method="post">
        <div class="card">
            <div class="card-body ">
                @csrf
               <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="paciente_id" class="control-label form-control-label">Paciente: <span class="text-danger">*</span></label>
                        <select class="form-control select2agenda" name="paciente_id" id="paciente_id" style="width: 100%">
                        <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group" id="agendamentos_class" style="display: none">
                        <label for="atendimento_id" class="control-label form-control-label">Atendimentos: <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="atendimento_id" id="atendimento_id" style="width: 100%">
                            <option value="">Selecione um atendimento</option>
                        </select>
                    </div>
               </div>
            </div>
        </div>

        <div class="card dados-agendamentos-card" style="display: none">
            <div class="card-body dados-agendamentos">
                
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="form-group text-right pb-2" style="margin-top: 10px">
                    <a href="{{ route('instituicao.contasAmbulatorial.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
                
            </div>
        </div>
    </form>


@endsection


@push('scripts')
    <script>
        $(document).ready(function(){
            $(".select2agenda").select2({
                placeholder: "Pesquise por nome ou cpf",
                allowClear: true,
                minimumInputLength: 3,
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

            }).on('select2:select', function (e) {
                var data = e.params.data;
                $("#agendamentos_class").css('display', 'block')
                $(".dados-agendamentos-card").css('display', 'none');
                $(".dados-agendamentos").html("");
                
                $.ajax({
                    url: "{{route('instituicao.contasAmbulatorial.getAgendamentos', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', data.id),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                        },
                    datatype: "json",
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        if(result != null){
                            agendamentos = result;
                                
                            $("#atendimento_id").find('option').filter(':not([value=""])').remove();
                            $.each(agendamentos, function (key, value) {
                                var data = new Date(value.data);
                                var mes = data.getMonth()
                                mes = mes+1;
                                if(mes < 10){
                                    mes = "0"+mes;
                                }
                                var dia = `${(data.getDate())}/${(mes)}/${data.getFullYear()} ${data.getHours()}:${data.getMinutes()}`

                                $("#atendimento_id").append('<option value='+value.id+'>'+value.instituicoes_agenda.prestadores.prestador.nome+' ('+dia+')</option>')

                            });
                        }else{
                            
                            $("#atendimento_id").find('option').filter(':not([value=""])').remove();
                            
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                    }
                });
                
            })
        })

        $("#atendimento_id").on('change', function(){
            var agendamento_id = $("#atendimento_id option:selected").val()
            if(agendamento_id){

                $.ajax({
                    url: "{{route('instituicao.contasAmbulatorial.getDadosAgendamentos', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento_id),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                        },
                    datatype: "json",
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".dados-agendamentos-card").css('display', 'block');
                        $(".dados-agendamentos").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                    }
                });
            }else{
                $(".dados-agendamentos-card").css('display', 'none');
                $(".dados-agendamentos").html("");
            }
        })
    </script>
@endpush
