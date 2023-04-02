@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Agenda ausente',
        'breadcrumb' => [
            'Agenda' => route('instituicao.prestadores.agendaAusente.index', [$prestador]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.agendaAusente.store', [$prestador]) }}" method="post">
                @csrf

                <div class="row">
                    <input type="hidden" name="prestador_id", id="prestador_id" value="{{ $prestador->id }}"/>
                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Data de ausência</label>
                        <input type="date" name="data" id="data" class="form-control @if($errors->has('data')) form-control-danger @endif " value="{{ old('data') }}"/>
                        @if($errors->has('data'))
                            <small class="form-control-feedback">{{ $errors->first('data') }}</small>
                        @endif
                    </div>

                    <div class="col-sm-2 p-4 m-0">
                        <div class="form-check mr-2 ml-2">
                            <input type="checkbox" class="form-check-input" name="dia_todo" value="1" @if(old('dia_todo')=="1") checked @endif id="dia_todo">
                            <label class="form-check-label" for="dia_todo">Dia todo? </label>
                        </div>
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Hora inicio</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control @if($errors->has('hora_inicio')) form-control-danger @endif " value="{{ old('hora_inicio') }}"/>
                        @if($errors->has('hora_inicio'))
                            <small class="form-control-feedback">{{ $errors->first('hora_inicio') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group">
                        <label class="form-control-label p-0 m-0">Hora fim</label>
                        <input type="time" name="hora_fim" id="hora_fim" class="form-control @if($errors->has('hora_fim')) form-control-danger @endif" value="{{ old('hora_fim') }}"/>
                        @if($errors->has('hora_fim'))
                            <small class="form-control-feedback">{{ $errors->first('hora_fim') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label class="form-control-label p-0 m-0">Motivo</label>
                        <input type="text" name="motivo" id="motivo" class="form-control @if($errors->has('motivo')) form-control-danger @endif" value="{{ old('motivo') }}"/>
                        @if($errors->has('motivo'))
                            <small class="form-control-feedback">{{ $errors->first('motivo') }}</small>
                        @endif
                    </div>

                    <div class="col-sm-2 pt-4 m-0">
                        <div class="form-check mr-2 ml-2">
                            <input type="checkbox" class="form-check-input" name="repetir" value="1" id="repetir">
                            <label class="form-check-label" for="repetir">Repetir? </label>
                        </div>
                    </div>

                    <div class="col-sm-3 form-group">
                        <label class="form-control-label p-0 m-0">Repetir até</label>
                        <input type="date" name="repetir_data" id="repetir_data" class="form-control @if($errors->has('repetir_data')) form-control-danger @endif " disabled/>
                        @if($errors->has('repetir_data'))
                            <small class="form-control-feedback">{{ $errors->first('repetir_data') }}</small>
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
            $('#dia_todo').on('change',function(){
                if($('#dia_todo').is(':checked')){
                    $('#hora_inicio').prop('disabled', true)
                    $('#hora_fim').prop('disabled', true)
                }else{
                    $('#hora_inicio').prop('disabled', false)
                    $('#hora_fim').prop('disabled', false)
                }
            })

            $('#repetir').on('change',function(){
                if($('#repetir').is(':checked')){
                    $('#repetir_data').prop('disabled', false)
                }else{
                    $('#repetir_data').prop('disabled', true)
                }
            })
        })
    </script>
@endpush
