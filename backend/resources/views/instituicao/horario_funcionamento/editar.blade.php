@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar horarios funcionamento",
        'breadcrumb' => [
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.horarios_funcionamento.update') }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                @foreach ($horarios as $item)
                    <div class="col-md-12">
                        <h4>{{ App\HorarioFuncionamentoInstituicao::ConvertDiaSemana($item->dia_semana) }}</h4>
                        <hr>
                        <div class="row">
                            <input type="hidden"  value="{{$item->id}}" name="horario[id][{{$loop->index}}]">
                            <div class="col-md-4 form-group @if($errors->has("horario.horario_inicio.{$loop->index}")) has-danger @endif">
                                <label class="form-control-label">Horario Inicio:</label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" id="horario[horario_inicio][{{$loop->index}}]" class="form-control" value="{{ old("horario.horario_inicio.{$loop->index}", $item->horario_inicio) }}" name="horario[horario_inicio][{{$loop->index}}]" @if($errors->has("horario.horario_inicio.{$loop->index}")) form-control-danger @endif>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                    @if($errors->has("horario.horario_inicio.{$loop->index}"))
                                    <div class="form-control-feedback">{{ $errors->first("horario.horario_inicio.{$loop->index}") }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 @if($errors->has("horario.horario_fim.{$loop->index}")) has-danger @endif">
                                <label class="form-control-label">Horario Fim:</label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control" value="{{ old("horario.horario_fim.{$loop->index}", $item->horario_fim) }}" id="horario[horario_fim][{{$loop->index}}]" name="horario[horario_fim][{{$loop->index}}]" @if($errors->has("horario.horario_fim.{$loop->index}")) form-control-danger @endif>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                    @if($errors->has("horario.horario_fim.{$loop->index}"))
                                    <div class="form-control-feedback">{{ $errors->first("horario.horario_fim.{$loop->index}") }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 row">
                                <div class="col-md-6 @if($errors->has("horario.full_time.{$loop->index}")) has-danger @endif" style="margin-top: 45px;">
                                    <input type="checkbox" id="horario[full_time][{{$loop->index}}]" name="horario[full_time][{{$loop->index}}]" class="filled-in"  @if ($item->full_time == 1)
                                        checked
                                    @endif onclick="verifica({{$loop->index}})" />
                                    <label for="horario[full_time][{{$loop->index}}]">24 Horas<label>
                                </div>

                                <div class="col-md-6 @if($errors->has("horario.fechado.{$loop->index}")) has-danger @endif" style="margin-top: 45px;">
                                    <input type="checkbox" id="horario[fechado][{{$loop->index}}]" name="horario[fechado][{{$loop->index}}]" class="filled-in"  @if ($item->fechado == 1)
                                        checked
                                    @endif onclick="verificaFechado({{$loop->index}})" />
                                    <label for="horario[fechado][{{$loop->index}}]">Fechado<label>
                                </div>
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed #000000fa">
                    </div>
                @endforeach

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts');
    <script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script>
        $(document).ready( function() {
            for (let index = 0; index < 7; index++) {
                verifica(index)
                verificaFechadoInicio(index)
            }
        })

        $('.clockpicker').clockpicker({
            donetext: 'Done',
        })

        function verifica(key){
            if ($("[name='horario[full_time]["+key+"]']").is(':checked') == true) {
                $("[name='horario[horario_inicio]["+key+"]']").attr('disabled', true);
                $("[name='horario[horario_fim]["+key+"]']").attr('disabled', true);
            }else{
                $("[name='horario[horario_inicio]["+key+"]']").attr('disabled', false);
                $("[name='horario[horario_fim]["+key+"]']").attr('disabled', false);
            }
        }

        function verificaFechado(key){
            if ($("[name='horario[fechado]["+key+"]']").is(':checked') == true) {
                $("[name='horario[horario_inicio]["+key+"]']").attr('disabled', true);
                $("[name='horario[horario_fim]["+key+"]']").attr('disabled', true);
                $("[name='horario[full_time]["+key+"]']").attr('disabled', true);
                $("[name='horario[full_time]["+key+"]']").attr('checked', false);
            }else{
                $("[name='horario[horario_inicio]["+key+"]']").attr('disabled', false);
                $("[name='horario[horario_fim]["+key+"]']").attr('disabled', false);
                $("[name='horario[full_time]["+key+"]']").attr('disabled', false);
            }
        }

        function verificaFechadoInicio(key){
            if ($("[name='horario[fechado]["+key+"]']").is(':checked') == true) {
                $("[name='horario[horario_inicio]["+key+"]']").attr('disabled', true);
                $("[name='horario[horario_fim]["+key+"]']").attr('disabled', true);
                $("[name='horario[full_time]["+key+"]']").attr('disabled', true);
                $("[name='horario[full_time]["+key+"]']").attr('checked', false);
            }
        }


    </script>
@endpush
