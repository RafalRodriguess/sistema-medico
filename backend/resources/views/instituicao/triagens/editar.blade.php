@extends('instituicao.layout')
@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => "Triagem #{$triagem->id}",
            'breadcrumb' => [
                'Senhas para triagem' => route('instituicao.triagens.index'),
                'Triagem',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.triagens.update', $triagem) }}" method="post">
                @method('put')
                @csrf

                <h3>Senha - <b>{{ $triagem->senha }}</b></h3>
                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('queixa')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Queixa <span class="text-danger">*</span></label>
                        <textarea rows="3" type="text" name="queixa"
                            class="form-control @if ($errors->has('queixa')) form-control-danger @endif">{{ old('queixa', $triagem->queixa) }}</textarea>
                        @if ($errors->has('queixa'))
                            <small class="form-control-feedback">{{ $errors->first('queixa') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('sinais_vitais')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Sinais vitais <span class="text-danger">*</span></label>
                        <textarea rows="4" type="text" name="sinais_vitais"
                            class="form-control @if ($errors->has('sinais_vitais')) form-control-danger @endif">{{ old('sinais_vitais', $triagem->sinais_vitais) }}</textarea>
                        @if ($errors->has('sinais_vitais'))
                            <small class="form-control-feedback">{{ $errors->first('sinais_vitais') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('doencas_cronicas')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Doenças Crônicas</label>
                        <textarea rows="4" type="text" name="doencas_cronicas"
                            class="form-control @if ($errors->has('doencas_cronicas')) form-control-danger @endif">{{ old('doencas_cronicas', $triagem->doencas_cronicas) }}</textarea>
                        @if ($errors->has('doencas_cronicas'))
                            <small class="form-control-feedback">{{ $errors->first('doencas_cronicas') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('alergias')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Alergias</label>
                        <textarea rows="4" type="text" name="alergias"
                            class="form-control @if ($errors->has('alergias')) form-control-danger @endif">{{ old('alergias', $triagem->alergias) }}</textarea>
                        @if ($errors->has('alergias'))
                            <small class="form-control-feedback">{{ $errors->first('alergias') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-4 form-group @if ($errors->has('classificacoes_triagem_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Classificação <span class="text-danger">*</span></label>
                        <div class="input-group container-classificacao">
                            <span id="color-preview" class="btn"></span>
                            <div class="col p-0">
                                <select name="classificacoes_triagem_id" id="select-classificacoes" style="width: 100%"
                                    class="form-control  @if ($errors->has('classificacoes_triagem_id')) form-control-danger @endif">
                                    <option value=""></option>
                                    @foreach ($classificacoes as $classificacao)
                                        <option @if (old('classificacoes_triagem_id', $triagem->classificacoes_triagem_id) == $classificacao->id) selected="selected" @endif
                                            value="{{ $classificacao->id }}" color="{{ $classificacao->cor }}">
                                            {{ $classificacao->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('classificacoes_triagem_id'))
                                <small
                                    class="form-control-feedback">{{ $errors->first('classificacoes_triagem_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="mr-4 form-group pb-2 d-flex align-items-end">
                        <div class="mr-2">
                            <input type="checkbox" name="primeiro_atendimento" id="primeiro_atendimento"
                                @if (!empty(old('primeiro_atendimento', $triagem->primeiro_atendimento))) checked="checked" @endif>
                        </div>
                        <label class="form-control-label p-0 m-0">Primeiro atendimento</label>
                    </div>
                    <div class="mr-4 form-group pb-2 d-flex align-items-end">
                        <div class="mr-2">
                            <input type="checkbox" name="reincidencia" id="reincidencia"
                                @if (!empty(old('reincidencia', $triagem->reincidencia))) checked="checked" @endif>
                        </div>
                        <label class="form-control-label p-0 m-0">Retorno com mesma queixa</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <hr>
                    </div>
                    <div class="col-md-6 col-sm-10 form-group @if ($errors->has('instituicoes_prestadores_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prestador </label>
                        <select id="prestador-select" name="prestador_id" style="width: 100%"
                            class="form-control  @if ($errors->has('instituicoes_prestadores_id')) form-control-danger @endif">
                            @if (!empty($prestador_escolhido))
                                <option value="{{ $prestador_escolhido->id }}">{{ $prestador_escolhido->nome }}</option>
                            @endif
                        </select>
                        @if ($errors->has('instituicoes_prestadores_id'))
                            <small
                                class="form-control-feedback">{{ $errors->first('instituicoes_prestadores_id') }}</small>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-10 form-group @if ($errors->has('especialidades')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Especialidades </label>
                        <select multiple id="especialidades-select" name="especialidades[]" style="width: 100%"
                            class="form-control  @if ($errors->has('especialidades')) form-control-danger @endif">
                            @foreach ($especialidades_escolhidas as $especialidade)
                                <option value="{{ $especialidade->id }}" selected>{{ $especialidade->descricao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('especialidades'))
                            <small class="form-control-feedback">{{ $errors->first('especialidades') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <hr>
                    </div>
                    <div class=" col-md-4 form-group pb-2 d-flex align-items-end">
                        <div class="mr-2">
                            <input type="checkbox" name="paciente_cadastrado" id="busca-de-pacientes-check"
                                @if (!empty(old('paciente_cadastrado', $triagem->pessoa_id))) checked="checked" @endif>
                        </div>
                        <label class="form-control-label p-0 m-0">Paciente cadastrado</label>
                    </div>
                    <div class="row col-12" style="display: none" id="nome-paciente-container">
                        <div class="col-md-6 form-group @if ($errors->has('paciente_nome')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome do paciente <span
                                    class="text-danger">*</span></label>
                            <input id="input-nome-paciente" type="text" name="paciente_nome"
                                value="{{ old('paciente_nome', $triagem->paciente_nome) }}"
                                class="form-control @if ($errors->has('paciente_nome')) form-control-danger @endif">
                            @if ($errors->has('paciente_nome'))
                                <small class="form-control-feedback">{{ $errors->first('paciente_nome') }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 form-group @if ($errors->has('paciente_mae')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome da mãe</label>
                            <input id="input-nome-mae" type="text" name="paciente_mae"
                                value="{{ old('paciente_mae', $triagem->paciente ? $triagem->paciente->paciente_mae : null) }}"
                                class="form-control @if ($errors->has('paciente_mae')) form-control-danger @endif">
                            @if ($errors->has('paciente_mae'))
                                <small class="form-control-feedback">{{ $errors->first('paciente_mae') }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 form-group @if ($errors->has('paciente_cpf')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CPF</label>
                            <input id="input-cpf" type="text" name="paciente_cpf"
                                value="{{ old('paciente_cpf', $triagem->paciente ? $triagem->paciente->paciente_cpf : null) }}"
                                class="form-control @if ($errors->has('paciente_cpf')) form-control-danger @endif">
                            @if ($errors->has('paciente_cpf'))
                                <small class="form-control-feedback">{{ $errors->first('paciente_cpf') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 p-0" style="display: none" id="paciente-busca-container">
                        <div class="col-md-8 col-sm-10 form-group @if ($errors->has('pessoa_id')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Paciente <span class="text-danger">*</span></label>
                            <select name="pessoa_id" id="pessoa_id" style="width: 100%"
                                class="form-control  @if ($errors->has('pessoa_id')) form-control-danger @endif">
                                @if (!empty($paciente_escolhido))
                                    <option value="{{ $paciente_escolhido->id }}" selected>
                                        {{ $paciente_escolhido->nome }} @if (!empty($paciente_escolhido->cpf)) - ({{ $paciente_escolhido->cpf }}) @endif
                                    </option>
                                @endif
                                <option value=""></option>
                            </select>
                            @if ($errors->has('pessoa_id'))
                                <small class="form-control-feedback">{{ $errors->first('pessoa_id') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.triagens.index') }}"
                        class="btn btn-secondary waves-effect waves-light m-r-10">Voltar</a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('estilos')
    <style>
        #color-preview {
            pointer-events: none;
            border-radius: unset;
            border: solid 1px #ced4da;
            background-color: #ced4da;
        }

        .container-classificacao .select2-selection.select2-selection--single {
            border-radius: 0 4px 4px 0 !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function handlePacienteTab(value = null) {
            value = value ? value : $('#busca-de-pacientes-check').prop('checked');
            // vou mudar isso, ta baguncado
            // so setar como disabled os input
            if (value) {
                $('#nome-paciente-container').hide();
                $('#nome-paciente-container').find('input').val(null);
                $('#paciente-busca-container').show();
            } else {
                $('#nome-paciente-container').show();
                $('#paciente-busca-container').hide();
                $('#paciente-busca-container').find('select').val(null).trigger('change');
            }
        }

        $(document).ready(function() {
            $("#pessoa_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('instituicao.contasPagar.getPacientes') }}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function(data, params) {
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
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando pacientes';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
            });

            $('#prestador-select').select2({
                placeholder: "Busque o prestador",
                ajax: {
                    url: "{{ route('instituicao.ajax.buscaprestador') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.results, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.prestador.nome
                                };
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        }
                    }
                },
                language: {
                    searching: function() {
                        return 'Buscando ...';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            }).on('select2:select', () => {
                $('#especialidades-select').val(null).trigger('change');
            });

            $('#especialidades-select').select2({
                placeholder: "Busque dentre especialidades do prestador",
                ajax: {
                    url: "{{ route('instituicao.ajax.buscarespecialidades') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    multiple: true,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.descricao
                                };
                            })
                        }
                    }
                },
                language: {
                    searching: function() {
                        return 'Buscando ...';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado, verifique foi selecionado um prestador com especialidades';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });

            // Mudar a cor de preview da classificação da triagem
            $('#select-classificacoes').select2({
                placeholder: 'Selecione um identificador',
            }).on('select2:select', (e) => {
                const color = $('#select-classificacoes').children(
                    `[value="${$('#select-classificacoes').val()}"]`).attr('color');
                $('#color-preview').css('background-color', color);
                $('#color-preview').css('border-color', color);
            });
            // Inicializando a checkbox
            $('#busca-de-pacientes-check').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
            }).on('ifChanged', function(event) {
                handlePacienteTab(event.target.checked);
            });

            $('#primeiro_atendimento, #reincidencia').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
            });

            // Inicializando a cor
            $('#color-preview').css('background-color', $('#select-classificacoes').children(
                `[value="${$('#select-classificacoes').val()}"]`).attr('color'))
            handlePacienteTab("{{ old('paciente_cadastrado') }}");

            $('#input-cpf').setMask('999.999.999-99', {
                translation: {
                    '9': {
                        pattern: /[0-9]/,
                        optional: false
                    }
                }
            })
        })
        ///VOCE TEM ALGUM OUTRO NAVEGADOR W????
        //  por que ? ta dando error no seu navegador
        // entao os cliente so pode usar chrome?
        // isso ai é uma coisa que veremos depois, se n voce tem q ver oq ta rolando no js de mask q ta com erro
    </script>
@endpush
