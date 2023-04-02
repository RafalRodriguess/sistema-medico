@extends('instituicao.layout')
@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => "Visualizar triagem {$triagem->id}",
            'breadcrumb' => [
                'Senhas para triagem' => route('instituicao.triagens.index'),
                'Visualizar triagem',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <div class="row">
                <div class="col-12 mb-2">
                    <span>Senha: </span>
                    <h5 class="d-inline"><b>{{ $triagem->senha }}</b></h5>
                </div>
                @if (!empty($triagem->prestador))
                    <div class="col-12 mb-2">
                        <span>Prestador: </span>
                        <span>{{ $triagem->prestador->nome }}</span>
                    </div>
                @endif
                @php
                    $especialidades = $triagem->especialidades;
                    $quant = $especialidades ? $especialidades->count() : 0;
                @endphp
                @if ($quant > 0)
                <div class="col-12 mb-2">
                    <span>Especialidades: </span>
                    @foreach ($especialidades as $especialidade)
                        <span class="btn-sm btn btn-secondary d-inline-block mx-1 my-0"
                            disabled>{{ ucfirst($especialidade->descricao) }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-md-8 col-sm-10 form-group">
                    <label class="form-control-label p-0 m-0">Paciente: </label>
                    <b>{{ $triagem->getPaciente()->nome }}</b>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-8 form-group">
                    <label class="form-control-label p-0 m-0">Queixa </label>
                    <textarea rows="3" type="text" name="queixa" class="form-control readonly">{{ $triagem->queixa }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-8 form-group">
                    <label class="form-control-label p-0 m-0">Sinais vitais </label>
                    <textarea rows="4" type="text" name="sinais_vitais" class="form-control readonly">{{ $triagem->sinais_vitais }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-8 form-group">
                    <label class="form-control-label p-0 m-0">Doenças Crônicas</label>
                    <textarea rows="4" type="text" name="doencas_cronicas" class="form-control readonly">{{ $triagem->doencas_cronicas }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-8 form-group">
                    <label class="form-control-label p-0 m-0">Alergias</label>
                    <textarea rows="4" type="text" name="alergias" class="form-control readonly">{{ $triagem->alergias }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-4 form-group">
                    <label class="form-control-label p-0 m-0">Identificador</label>
                    <div class="input-group">
                        <span id="color-preview"
                            style="pointer-events: none; font-weight: bold; background-color: {{ $triagem->classificacao->cor }}"
                            class="btn"></span>
                        <span class="form-control">{{ $triagem->classificacao->descricao }}</span>
                    </div>
                </div>
                <div class="mr-4 form-group pb-2 d-flex align-items-end">
                    <div class="mr-2">
                        <input class="readonly" type="checkbox" name="primeiro_atendimento" id="primeiro_atendimento"
                            @if (!empty($triagem->primeiro_atendimento)) checked="checked" @endif>
                    </div>
                    <label class="form-control-label p-0 m-0">Primeiro atendimento</label>
                </div>
                <div class="mr-4 form-group pb-2 d-flex align-items-end">
                    <div class="mr-2">
                        <input class="readonly" type="checkbox" name="reincidencia" id="reincidencia"
                            @if (!empty($triagem->reincidencia)) checked="checked" @endif>
                    </div>
                    <label class="form-control-label p-0 m-0">Retorno com mesma queixa</label>
                </div>
            </div>

            <div class="form-group text-right pb-2">
                <a href="{{ route('instituicao.triagens.index') }}"
                    class="btn btn-secondary waves-effect waves-light m-r-10">Voltar</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#primeiro_atendimento, #reincidencia').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
            });
        })
    </script>
@endpush
