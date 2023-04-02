@extends('instituicao.layout')

@push('estilos')
    <style>
        .select2{
            width: 100%!important;
        }
    </style>
@endpush

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Cirurgia #{$cirurgia->id} {$cirurgia->descricao}",
        'breadcrumb' => [
            'Cirurgia' => route('instituicao.cirurgias.index'),
            'Atualização',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">

            <form action="{{ route('instituicao.cirurgias.update', [$cirurgia]) }}" method="post">
                @method('put')
                @csrf
               <div class="row">
                    <div class=" col-md form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $cirurgia->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md form-group @if($errors->has('porte')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Porte</label>
                        <select class="form-control select2 @if($errors->has('porte')) form-control-danger @endif" name="porte" id="porte">
                            <option value="">Selecione</option>
                            @foreach (\App\Cirurgia::opcoes_porte as $item)
                                <option {{(old('porte', $cirurgia->porte) == $item) ? 'selected' : '' }} value="{{ $item }}">{{ \App\Cirurgia::getPortes($item) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('porte'))
                            <small class="form-control-feedback">{{ $errors->first('porte') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-2 form-group @if($errors->has('previsao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Previsão <small>Em minutos</small></label>
                        <input type="text" name="previsao" value="{{ old('previsao', $cirurgia->previsao) }}"
                        class="form-control @if($errors->has('previsao')) form-control-danger @endif">
                        @if($errors->has('previsao'))
                            <small class="form-control-feedback">{{ $errors->first('previsao') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="shadow-none p-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input campo" name="obstetricia" value="1" id="obstetricia" {{(old('previsao', $cirurgia->obstetricia)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="obstetricia">Obstetrico?</label>
                        </div>
                    </div>

                    <div class="col-md form-group @if($errors->has('tipo_parto_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo de parto</label>
                        <select class="obstetrico form-control @if($errors->has('tipo_parto_id')) form-control-danger @endif" name="tipo_parto_id" id="tipo_parto_id">
                            <option value="0">Selecione</option>
                            @foreach ($partos as $item)
                                <option {{(old('tipo_parto_id', $cirurgia->tipo_parto_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_parto_id'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_parto_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('grupo_cirurgia_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Grupo Crirurgia</label>
                        <select class="form-control @if($errors->has('grupo_cirurgia_id')) form-control-danger @endif" name="grupo_cirurgia_id" id="grupo_cirurgia_id">
                            <option value="">Selecione</option>
                            @foreach ($gruposCirurgias as $item)
                                <option {{(old('grupo_cirurgia_id', $cirurgia->grupo_cirurgia_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('grupo_cirurgia_id'))
                            <small class="form-control-feedback">{{ $errors->first('grupo_cirurgia_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('via_acesso_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Via Acesso</label>
                        <select class="form-control @if($errors->has('via_acesso_id')) form-control-danger @endif" name="via_acesso_id" id="via_acesso_id">
                            <option value="0">Selecione</option>
                            @foreach ($viasAcesso as $item)
                                <option {{(old('via_acesso_id', $cirurgia->via_acesso_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('via_acesso_id'))
                            <small class="form-control-feedback">{{ $errors->first('via_acesso_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-md form-group @if($errors->has('tipo_anestesia_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipos de Anestesia</label>
                        <select class="form-control @if($errors->has('tipo_anestesia_id')) form-control-danger @endif" name="tipo_anestesia_id" id="tipo_anestesia_id">
                            <option value="0">Selecione</option>
                            @foreach ($tipoAnestesias as $item)
                                <option {{(old('tipo_anestesia_id', $cirurgia->tipo_anestesia_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_anestesia_id'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_anestesia_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('convenio_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Convênio</label>
                        <select class="form-control select2 @if($errors->has('convenio_id')) form-control-danger @endif" name="convenio_id" id="convenio_id" onchange="getProcedimentos(this)">
                            <option value="">Selecione</option>
                            @foreach ($convenios as $item)
                                <option @if (old("convenio_id", $cirurgia->convenio_id) == $item->id)
                                    selected
                                @endif value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('convenio_id'))
                            <small class="form-control-feedback">{{ $errors->first('convenio_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('procedimento_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Procedimentos</label>
                        <select class="form-control select2 @if($errors->has('procedimento_id')) form-control-danger @endif" name="procedimento_id" id="procedimento_id">
                            <option value="{{$cirurgia->procedimento->id}}">{{$cirurgia->procedimento->descricao}}</option>
                            {{-- @foreach ($procedimentos as $item)
                                <option {{(old('procedimento_id', $cirurgia->procedimento_id) == $item->id) ? 'selected' : '' }}    value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach --}}
                        </select>
                        @if($errors->has('procedimento_id'))
                            <small class="form-control-feedback">{{ $errors->first('procedimento_id') }}</small>
                        @endif
                    </div>

                    
                </div>

                <div class='row'>
                    <div class=" col-md form-group @if($errors->has('orientacoes')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Orientações</label>
                        <textarea rows='5' name="orientacoes" class="form-control @if($errors->has('orientacoes')) form-control-danger @endif">{{ old('orientacoes', $cirurgia->orientacoes) }}</textarea>
                        @if($errors->has('orientacoes'))
                            <small class="form-control-feedback">{{ $errors->first('orientacoes') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class=" col-md form-group @if($errors->has('preparos')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Preparos</label>
                        <textarea rows='5' name="preparos" class="form-control @if($errors->has('preparos')) form-control-danger @endif">{{ old('preparos', $cirurgia->preparos) }}</textarea>
                        @if($errors->has('preparos'))
                            <small class="form-control-feedback">{{ $errors->first('preparos') }}</small>
                        @endif
                    </div>
                </div>

                <div class='equipamentos'>
                    <div class="card">
                        <div class="col-sm-12 border-bottom bg-light p-3">
                            <label class="form-control-label p-0 m-0">Equipamentos</label>
                        </div>
                        <br>

                        @include('instituicao.cirurgias.equipamentos_editar')

                        <div class="form-group col-md-12 add-class-equipamento" >
                            <span alt="default" class="add-equipamento fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar Equipamento"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class='especialidades'>
                    <div class="card">
                        <div class="col-sm-12 border-bottom bg-light p-3">
                            <label class="form-control-label p-0 m-0">Especialidades</label>
                        </div>
                        <br>

                        @include('instituicao.cirurgias.especialidades_editar')

                        <div class="form-group col-md-12 add-class-especialidade" >
                            <span alt="default" class="add-especialidade fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar Especialidade"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class='equipes'>
                    <div class="card">
                        <div class="col-sm-12 border-bottom bg-light p-3">
                            <label class="form-control-label p-0 m-0">Equipe (Prestadores)</label>
                        </div>
                        <br>

                        @include('instituicao.cirurgias.equipes_editar')

                        <div class="form-group col-md-12 add-class-equipe" >
                            <span alt="default" class="add-equipe fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar equipe"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class='salas'>
                    <div class="card">
                        <div class="col-sm-12 border-bottom bg-light p-3">
                            <label class="form-control-label p-0 m-0">Salas cirurgicas</label>
                        </div>
                        <br>

                        @include('instituicao.cirurgias.salas_cirurgicas_editar')

                        <div class="form-group col-md-12 add-class-sala" >
                            <span alt="default" class="add-sala fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar centro de custo"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.cirurgias.index') }}">
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
            obistetricia()
        })

        $('#obstetricia').on('change', function(){
            obistetricia()
        })

        function obistetricia(){

            let a = $('#obstetricia').is(':checked')
            console.log(a)
            if(a){
                $('.obstetrico').prop('disabled',false)
            }else{
                $('.obstetrico').prop('disabled',true)
            }
        }

        function getProcedimentos(element){
            var id = $(element).val()
            var options = $("#procedimento_id");

            $.ajax({
                url: "{{route('instituicao.getProcedimentoVinculoConvenio', ['convenio' => 'convenio_id'])}}".replace('convenio_id', id),
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
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
                        procedimentos = result
                        options.prop('disabled', false);
                        options.find('option').filter(':not([value=""])').remove();
                        

                        $.each(procedimentos, function (key, value) {
                                    // $('<option').val(value.id).text(value.Nome).appendTo(options);
                            options.append('<option value='+value.procedimento.id+'>'+value.procedimento.descricao+'</option>')
                            //options += '<option value="' + key + '">' + value + '</option>';
                        });
                    }
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });

        }
    </script>
@endpush
