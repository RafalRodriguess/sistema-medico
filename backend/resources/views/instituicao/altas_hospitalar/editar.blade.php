@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "Canceçar Alta Hospitalar #{$alta_hospitalar->id}",
    'breadcrumb' => [
        'Alta Hospitalar' => route('instituicao.altasHospitalar.index'),
        "Canceçar Alta Hospitalar #{$alta_hospitalar->id}",
    ],
])
@endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.altasHospitalar.update', [$alta_hospitalar]) }}" method="post">
                @method('put')
                @csrf
                <div class="row paciente">
                    <div class="col-md form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <input type="hidden" name="paciente_id", id="paciente_id" value="{{ old('paciente_id', $internacao->paciente_id) }}"/>
                        <label class="form-control-label p-0 m-0">Paciente <span class="text-danger">*</span></label>
                        <input type="text" name="paciente_nome" id="paciente_nome" class="form-control" disabled/>

                        @if($errors->has('paciente_id'))
                            <small class="form-control-feedback">{{ $errors->first('paciente_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Internação Id</label>
                        <input type="text" readonly name="internacao_id" id="internacao_id" class="form-control" value="{{ old('internacao_id', $alta_hospitalar->internacao_id) }}"/>
                        @if($errors->has('internacao_id'))
                            <small class="form-control-feedback">{{ $errors->first('internacao_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Atendimento Id</label>
                        <input type="text" readonly name="atendimento_id" id="atendimento_id" class="form-control" value="{{ old('atendimento_id', $alta_hospitalar->atendimento_id) }}"/>
                        @if($errors->has('atendimento_id'))
                            <small class="form-control-feedback">{{ $errors->first('atendimento_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Data internação</label>
                        <input type="datetime-local" disabled name="data_internacao" id="data_internacao" class="form-control" value="{{ old('atendimento_id', $alta_hospitalar->data_internacao) }}"/>
                        @if($errors->has('data_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('data_internacao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Data alta</label>
                        <input disabled type="datetime-local" name="data_alta" class="form-control" value="{{ old('data_alta', $alta_hospitalar->data_alta) }}"/>
                        @if($errors->has('data_alta'))
                            <small class="form-control-feedback">{{ $errors->first('data_alta') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Motivo de alta</label>
                        <select disabled class="form-control p-0 m-0 selectfild2" name="motivo_alta_id" id="motivo_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($motivoAlta as $item)
                                <option {{ (old('motivo_alta_id', $alta_hospitalar->motivo_alta_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao_motivo_alta }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('motivo_alta_id'))
                            <small class="form-control-feedback">{{ $errors->first('motivo_alta_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="form-control-label p-0 m-0">Infecção</label>
                        <select disabled class="form-control p-0 m-0" name="infeccao_alta" id="infeccao_alta">
                            <option value="0" {{ (old('infeccao_alta', $alta_hospitalar->infeccao_alta) == 0) ? 'selected' : '' }} >Não</option>
                            <option value="1" {{ (old('infeccao_alta', $alta_hospitalar->infeccao_alta) == 1) ? 'selected' : '' }} >Sim</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Declaração de óbito</label>
                        <input disabled type="text" name="declaracao_obito_alta" class="form-control" value="{{ old('declaracao_obito_alta', $alta_hospitalar->declaracao_obito_alta) }}"/>
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Procedimento de alta</label>
                        <select disabled class="form-control p-0 m-0 selectfild2" name="procedimento_alta_id" id="procedimento_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($procedimentos as $item)
                                <option {{ (old('procedimento_alta_id', $alta_hospitalar->procedimento_alta_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Especialidade de alta</label>
                        <select disabled class="form-control p-0 m-0 selectfild2" name="especialidade_alta_id" id="especialidade_alta_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($especialidades as $item)
                                <option {{ (old('especialidade_alta_id', $alta_hospitalar->especialidade_alta_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('obs_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observação</label>
                        <textarea disabled rows='4' class="form-control @if($errors->has('obs_alta')) form-control-danger @endif" name="obs_alta" id="obs_alta">{{ old('obs_alta', $alta_hospitalar->obs_alta) }}</textarea>
                        @if($errors->has('obs_alta'))
                            <small class="form-control-feedback">{{ $errors->first('obs_alta') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('motivo_cancel_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Motivo Cancelamento <span class="text-danger">*</span></label>
                        <input type='text' class="form-control @if($errors->has('motivo_cancel_alta')) form-control-danger @endif" name="motivo_cancel_alta" id="motivo_cancel_alta" value="{{ old('motivo_cancel_alta') }}" />
                        @if($errors->has('obs_alta'))
                            <small class="form-control-feedback">{{ $errors->first('obs_alta') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.altasHospitalar.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Cancelar Alta</button>
                </div>
            </div>
        </form>
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
    </script>
@endpush
