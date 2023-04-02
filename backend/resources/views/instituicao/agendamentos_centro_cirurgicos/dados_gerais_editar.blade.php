<div class="p-10">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Cirurgião Principal:</label>
            <input type="text" class="form-control centro_cirurgico_novo" id="centro_cirurgico_novo" name="centro_cirurgico_novo" value="{{$agendamento->cirurgiao->nome}}" disabled>
        </div>
        <div class="col-md-8"></div>
        <div class="col-md-12 form-group">
            {{-- <div class="row">
                <div class="col-md-4 "> --}}
                    <input type="radio" id="paciente" name="tipo_paciente" class="filled-in" value="1" @if ($agendamento->tipo_paciente == "paciente") checked  @endif/>
                    <label for="paciente">Paciente<label>
                {{-- </div>
                <div class="col-md-6 form-group"> --}}
                    <input type="radio" id="ambulatorio" name="tipo_paciente" class="filled-in" value="1" @if ($agendamento->tipo_paciente == "ambulatorio") checked  @endif/>
                    <label style="margin-left: 10px;" for="ambulatorio">Ambulatório<label>

                    <input type="radio" id="urgencia" name="tipo_paciente" class="filled-in" value="1" @if ($agendamento->tipo_paciente == "urgencia") checked  @endif/>
                    <label style="margin-left: 10px;" for="urgencia">Urgência<label>

                    <input type="radio" id="internacao" name="tipo_paciente" class="filled-in" value="1" @if ($agendamento->tipo_paciente == "internacao") checked  @endif/>
                    <label style="margin-left: 10px;" for="internacao">Internação<label>
                {{-- </div>
            </div> --}}
        </div>
        <div class="form-group col-md-12 paciente_tipo">
            <label for="centro_cirurgico_editar" class="control-label">Paciente:</label>
            <select name="paciente_id_editar" id="paciente_id_editar" class="form-control select2atendimentopaciente " style="width: 100%">
                <option value="">Selecione um paciente</option>
                @if ($agendamento->paciente_id)
                    <option value="{{$agendamento->paciente_id}}" selected>{{$agendamento->pessoa->nome}} {{($agendamento->pessoa->cpf) ? '- ('.$agendamento->pessoa->cpf.')': ''}} </option>
                @endif
                <option value=""></option>
                {{-- @foreach ($atendimento as $item)
                    <option value="{{$item->id}}" @if ($agendamento->paciente_id == $item->id)
                        selected
                    @endif>{{$item->pessoa->nome}} {{($item->pessoa->telefone1) ? $item->pessoa->telefone1 : $item->pessoa->telefone2}}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="form-group col-md-12 ambulatorio_tipo" style="display: none">
            <label for="centro_cirurgico_editar" class="control-label">Ambulatório:</label>
            <select name="ambulatorio_id_editar" id="ambulatorio_id_editar" class="form-control select2atendimentoambulatorio " style="width: 100%">
                <option value="">Selecione um paciente</option>
                @if ($agendamento->ambulatorio_id)
                    <option value="{{$agendamento->ambulatorio_id}}" selected>{{$agendamento->ambulatorio->pessoa->nome}} {{($agendamento->ambulatorio->pessoa->cpf) ? '- ('.$agendamento->pessoa->cpf.')': ''}} </option>
                @endif
                <option value=""></option>
                {{-- @foreach ($atendimento as $item)
                    <option value="{{$item->id}}" @if ($agendamento->paciente_id == $item->id)
                        selected
                    @endif>{{$item->pessoa->nome}} {{($item->pessoa->telefone1) ? $item->pessoa->telefone1 : $item->pessoa->telefone2}}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="form-group col-md-12 urgencia_tipo" style="display: none">
            <label for="centro_cirurgico_editar" class="control-label">Urgência:</label>
            <select name="urgencia_id_editar" id="urgencia_id_editar" class="form-control select2atendimentourgencia " style="width: 100%">
                <option value="">Selecione um paciente</option>
                @if ($agendamento->urgencia_id)
                    <option value="{{$agendamento->urgencia_id}}" selected>{{$agendamento->urgencia->paciente->nome}} {{($agendamento->urgencia->paciente->cpf) ? '- ('.$agendamento->pessoa->cpf.')': ''}} </option>
                @endif
                <option value=""></option>
                {{-- @foreach ($atendimento as $item)
                    <option value="{{$item->id}}" @if ($agendamento->paciente_id == $item->id)
                        selected
                    @endif>{{$item->pessoa->nome}} {{($item->pessoa->telefone1) ? $item->pessoa->telefone1 : $item->pessoa->telefone2}}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="form-group col-md-12 internacao_tipo" style="display: none">
            <label for="centro_cirurgico_editar" class="control-label">Internação:</label>
            <select name="internacao_id_editar" id="internacao_id_editar" class="form-control select2atendimentointernacao " style="width: 100%">
                <option value="">Selecione um paciente</option>
                @if ($agendamento->internacao_id)
                    <option value="{{$agendamento->internacao_id}}" selected>{{$agendamento->internacao->paciente->nome}} {{($agendamento->internacao->paciente->cpf) ? '- ('.$agendamento->pessoa->cpf.')': ''}} </option>
                @endif
                <option value=""></option>
                {{-- @foreach ($atendimento as $item)
                    <option value="{{$item->id}}" @if ($agendamento->paciente_id == $item->id)
                        selected
                    @endif>{{$item->pessoa->nome}} {{($item->pessoa->telefone1) ? $item->pessoa->telefone1 : $item->pessoa->telefone2}}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Acomodação *:</label>
            <select name="acomodacao_editar" id="acomodacao_editar" class="form-control select2editar" style="width: 100%">
                <option value="">Selecione uma acomodação</option>
                @foreach ($acomodacoes as $item)
                    <option value="{{$item->id}}" @if ($agendamento->acomodacao_id == $item->id)
                        selected
                    @endif>{{$item->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Unidade de internação:</label>
            <select name="unidade_internacao_editar" id="unidade_internacao_editar" class="form-control select2editar" style="width: 100%">
                <option value="">Selecione uma unidade</option>
                @foreach ($unidades_internacoes as $item)
                    <option value="{{$item->id}}" @if ($agendamento->unidade_internacao_id == $item->id)
                        selected
                    @endif>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Via de acesso *:</label>
            <select name="via_acesso_editar" id="via_acesso_editar" class="form-control select2editar" style="width: 100%">
                <option value="">Selecione uma via de acesso</option>
                @foreach ($vias_acesso as $item)
                    <option value="{{$item->id}}" 
                        @if ($agendamento->via_acesso_id == $item->id)
                            selected
                        @else
                            @if ($agendamento->cirurgia->via_acesso_id == $item->id && $agendamento->via_acesso_id == null)
                                selected
                            @endif
                        @endif>{{$item->descricao}}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Anestesista *:</label>
            <select name="anestesista_editar" id="anestesista_editar" class="form-control select2editar" style="width: 100%">
                <option value="">Selecione uma anestesista</option>
                @foreach ($prestadores as $item)
                    <option value="{{$item->prestador->id}}" @if ($agendamento->anestesista_id == $item->prestador->id)
                        selected
                    @endif>{{$item->prestador->nome}}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Tipo de anestesia *:</label>
            <select name="tipo_anestesia_editar" id="tipo_anestesia_editar" class="form-control select2editar" style="width: 100%">
                <option value="">Selecione uma anestesia</option>
                @foreach ($anestesias as $item)
                    <option value="{{$item->id}}" @if ($agendamento->tipo_anestesia_id == $item->id)
                        selected
                    @else
                        @if ($agendamento->cirurgia->tipo_anestesia_id == $item->id && $agendamento->tipo_anestesia_id == null)
                            selected
                        @endif
                    @endif>{{$item->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Pacote *:</label>
            <select name="pacote_editar" id="pacote_editar" class="form-control select2editar" style="width: 100%">
                <option value="0" @if ($agendamento->pacote == '0')
                        selected
                    @endif>Não</option>
                <option value="1" @if ($agendamento->pacote == '1')
                        selected
                    @endif>Sim</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">CID Pré-operatorio:</label>
            <select name="cid_editar" id="cid_editar" class="form-control select2cids" style="width: 100%">
                <option value="">Selecione uma CID</option>
                @if ($agendamento->cid_id)
                    <option value="{{$agendamento->cid_id}}" selected>{{$agendamento->cid->descricao}}</option>
                @endif
                {{-- @foreach ($cids as $item)
                    <option value="{{$item->id}}" @if ($agendamento->cid_id == $item->id)
                        selected
                    @endif>{{$item->descricao}}</option>
                @endforeach --}}
            </select>
        </div>
        <div class="form-group col-md-8">
            <label for="centro_cirurgico_editar" class="control-label">Observações</label>
            <textarea class="form-control" name="obs_editar" id="obs_editar" cols="5" rows="5">@if ($agendamento->obs == null) @if($agendamento->cirurgia->orientacoes) Orientações: {{$agendamento->cirurgia->orientacoes}}; @endif @if($agendamento->cirurgia->preparos) Preparos: {{$agendamento->cirurgia->preparos}} @endif @else {{$agendamento->obs}} @endif</textarea>
        </div>
    </div>
</div>

<script>
    $(".select2editar").select2();

    $(document).ready(function(){
        verificaRadioChecked();
        $(".select2atendimentopaciente").select2({
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
        $(".select2atendimentoambulatorio").select2({
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
                url:"{{route('instituicao.agendamentoCentroCirurgico.getPacientes')}}",
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
                        text: `${item.pessoa.nome} ${(item.pessoa.cpf) ? '- ('+item.pessoa.cpf+')': ''} (${convertData(item.data)})`,
                    })),
                    pagination: {
                        more: data.pagination.more
                    }
                };
                },
                cache: true
            },

        })
        $(".select2atendimentourgencia").select2({
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
                url:"{{route('instituicao.agendamentoCentroCirurgico.getPacientesUrgencia')}}",
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
                        text: `${item.senha_triagem.paciente.nome} ${(item.senha_triagem.paciente.cpf) ? '- ('+item.senha_triagem.paciente.cpf+')': ''} (${convertData(item.data_hora)} - ${item.senha_triagem.classificacao.descricao})`,
                    })),
                    pagination: {
                        more: data.pagination.more
                    }
                };
                },
                cache: true
            },

        })
        $(".select2atendimentointernacao").select2({
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
                url:"{{route('instituicao.agendamentoCentroCirurgico.getPacientesInternacao')}}",
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
                        text: `${item.paciente.nome} ${(item.paciente.cpf) ? '- ('+item.paciente.cpf+')': ''}`,
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
        
        $(".select2cids").select2({
            placeholder: "Pesquise por descricao",
            allowClear: true,
            minimumInputLength: 3,
            language: {
            searching: function () {
                return 'Buscando cids (aguarde antes de selecionar)…';
            },
            
            inputTooShort: function (input) {
                return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
            },
            },    
            
            ajax: {
                url:"{{route('instituicao.agendamentoCentroCirurgico.getCids')}}",
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
    // })

    $("input[name='tipo_paciente']").on('click', function(){
        verificaRadioChecked()
    })

    function verificaRadioChecked(){
        
        if($("input[name='tipo_paciente']:checked").attr('id') == "paciente"){
            camposDisplayENull('paciente', 'ambulatorio', 'urgencia', 'internacao')

        }else if($("input[name='tipo_paciente']:checked").attr('id') == "ambulatorio"){
            camposDisplayENull('ambulatorio', 'paciente', 'urgencia', 'internacao')

        }else if($("input[name='tipo_paciente']:checked").attr('id') == "urgencia"){
            camposDisplayENull('urgencia', 'ambulatorio', 'paciente', 'internacao')

        }else if($("input[name='tipo_paciente']:checked").attr('id') == "internacao"){
            camposDisplayENull('internacao', 'ambulatorio', 'urgencia', 'paciente')
        }
    }

    function camposDisplayENull(escolhido, campo1, campo2, campo3){
        $("."+escolhido+"_tipo").css('display', 'block');
        $("."+campo1+"_tipo").css('display', 'none');
        $("."+campo2+"_tipo").css('display', 'none');
        $("."+campo3+"_tipo").css('display', 'none');

        
        $("#"+campo1+"_id_editar").val('').change();
        $("#"+campo2+"_id_editar").val('').change();
        $("#"+campo3+"_id_editar").val('').change();
    }

    function convertData(data){
        var dados = data.split('T');
        var dados = dados[0].split(' ');
        var final = dados[0].split('-');
        return final[2]+'/'+final[1]+'/'+final[0];
    }
</script>