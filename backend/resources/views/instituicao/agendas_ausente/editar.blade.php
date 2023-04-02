@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "editar Agenda ausente #{$agendaAusente->id}",
    'breadcrumb' => [
        'Alta Agenda' => route('instituicao.altasHospitalar.index'),
        "Editar Agenda ausente #{$agendaAusente->id}",
    ],
])
@endcomponent


<div class="card col-sm-12">

    <div class="card-body">
        <form action="{{ route('instituicao.prestadores.agendaAusente.update', [$prestador , $agendaAusente]) }}" method="post">
            @method('put')
            @csrf

            <div class="row">
                <input type="hidden" name="prestador_id", id="prestador_id" value="{{ $prestador->id }}"/>
                <div class="col-md form-group">
                    <label class="form-control-label p-0 m-0">Data de ausência</label>
                    <input type="date" name="data" id="data" class="form-control @if($errors->has('data')) form-control-danger @endif " value="{{ old('data', $agendaAusente->data) }}"/>
                    @if($errors->has('data'))
                        <small class="form-control-feedback">{{ $errors->first('data') }}</small>
                    @endif
                </div>

                <div class="col-sm-2 p-4 m-0">
                    <div class="form-check mr-2 ml-2">
                        <input type="checkbox" class="form-check-input" name="dia_todo" value="1" @if(old('dia_todo', $agendaAusente->dia_todo)=="1") checked @endif id="dia_todo">
                        <label class="form-check-label" for="dia_todo">Dia todo? </label>
                    </div>
                </div>

                <div class="col-md form-group">
                    <label class="form-control-label p-0 m-0">Hora inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control @if($errors->has('hora_inicio')) form-control-danger @endif " value="{{ old('hora_inicio', $agendaAusente->hora_inicio) }}"/>
                    @if($errors->has('hora_inicio'))
                        <small class="form-control-feedback">{{ $errors->first('hora_inicio') }}</small>
                    @endif
                </div>

                <div class="col-md form-group">
                    <label class="form-control-label p-0 m-0">Hora fim</label>
                    <input type="time" name="hora_fim" id="hora_fim" class="form-control @if($errors->has('hora_fim')) form-control-danger @endif" value="{{ old('hora_fim', $agendaAusente->hora_fim) }}"/>
                    @if($errors->has('hora_fim'))
                        <small class="form-control-feedback">{{ $errors->first('hora_fim') }}</small>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md form-group">
                    <label class="form-control-label p-0 m-0">Motivo</label>
                    <input type="text" name="motivo" id="motivo" class="form-control @if($errors->has('motivo')) form-control-danger @endif" value="{{ old('motivo', $agendaAusente->motivo) }}"/>
                    @if($errors->has('motivo'))
                        <small class="form-control-feedback">{{ $errors->first('motivo') }}</small>
                    @endif
                </div>
            </div>



            <div class="form-group text-right pb-2">
                <a href="{{ route('instituicao.prestadores.agendaAusente.index', [$prestador]) }}">
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
            diaTodo()
        })

        $('#dia_todo').on('change',function(){
            diaTodo()
        })

        function diaTodo(){

                if($('#dia_todo').is(':checked')){
                    $('#hora_inicio').prop('disabled', true)
                    $('#hora_fim').prop('disabled', true)
                }else{
                    $('#hora_inicio').prop('disabled', false)
                    $('#hora_fim').prop('disabled', false)
                }
        }

    </script>
@endpush
