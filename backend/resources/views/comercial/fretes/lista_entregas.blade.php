@extends('comercial.layout')


@push('scripts')
<!-- jQuery peity -->
<script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
<script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
@endpush

@push('estilos')
<link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
@endpush

@section('conteudo')

<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@component('components/page-title', [
'titulo' => "Configuraçao de entrega de pedidos",
'breadcrumb' => [
'Fretes' => route('comercial.fretes_entregas'),
'Entregas',
],
])
@endcomponent
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <!-- Column -->
        <div class="card card-body">
            <form action="{{route('comercial.fretes_entregas.update_frete_entrega')}}" method="post">
                @csrf
                <span class="float-right">
                    <label for="">Filtro da entrega:</label> {{$configfrete->tipo_filtro}}
                </span>
                <h4 class="card-title">Configuração de fretes</h4>

                <div class="itemFrete">
                    <div class="row">

                        <div class="col-md-3">
                            <label for="">Status:</label>
                            <div class="switch">
                                <label> Desativado<input name="ativado" type="checkbox" @if ($configfrete->ativado ===1)
                                    checked
                                    @endif
                                    ><span class="lever"></span>Ativado
                                </label>
                            </div>

                        </div>



                        <div class="col-md-7">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-4 form-group @if($errors->has('valor')) has-danger @endif">
                                        <label class="form-control-label">Prazo em: *</label>
                                        <div class="input-group">
                                            <select name="tipo_prazo" class="form-control" id="selectPrazo">
                                                <option value="minutos" @if (old('tipo_prazo', $configfrete->tipo_prazo) == "minutos")
                                                    selected="selected"
                                                    @endif>Minutos</option>
                                                <option value="horas" @if (old('tipo_prazo', $configfrete->tipo_prazo) == "horas")
                                                    selected="selected"
                                                    @endif>Horas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 @if($errors->has('prazo_minimo')) has-danger @endif">
                                        <label class="form-control-label">Prazo mínimo: *</label>
                                        <div class="input-group ">
                                            <input type="text" name="prazo_minimo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazo" value="{{ old('prazo_minimo', $configfrete->prazo_minimo) }}">
                                            @if($errors->has('prazo_minimo'))
                                            <div class="form-control-feedback">{{ $errors->first('prazo_minimo') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 @if($errors->has('prazo_maximo')) has-danger @endif">
                                        <label class="form-control-label">Prazo máximo: *</label>
                                        <div class="input-group">
                                            <input type="text" name="prazo_maximo" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif inputPrazo" value="{{ old('prazo_maximo', $configfrete->prazo_maximo) }}">
                                            @if($errors->has('prazo_maximo'))
                                            <div class="form-control-feedback">{{ $errors->first('prazo_maximo') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>


                        <div class="col-md-2">

                            <button type="submit" class="btn btn-secondary btnfiltro" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-check-circle"></i> Salvar alterações
                            </button>
                        </div>

                    </div>
                </div>
            </form>

            @switch($configfrete->tipo_filtro)
            @case('cidade')
            @livewire('comercial.fretes-cidades-pesquisa')
            @break

            @case('cidade_bairro')
            @livewire('comercial.fretes-bairros-pesquisa')
            @break

            @case('faixa_cep')
            @livewire('comercial.fretes-faixacep-pesquisa')
            @break

            @case('cep_unico')
            @livewire('comercial.fretes-cepunico-pesquisa')
            @break

            @endswitch


        </div>
        <!-- Column -->

        <!-- Column -->

    </div>
</div>

@endsection


<style>
    .itemFrete {
        padding: 15px 0px;
        border-top: solid 1px #a7a7a79e;
        border-bottom: solid 1px #a7a7a79e;
    }

    .btnfiltro {
        width: 100%;
        margin-top: 31px;

    }
</style>



@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectPrazo').change(function() {
            $('.inputPrazo').val('')
        })
        $(".inputPrazo").TouchSpin({
            min: 0,
            max: 200,
            step: 1,
        });

    });
</script>
@endpush