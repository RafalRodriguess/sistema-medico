@extends('comercial.layout')


@push('scripts')
<!-- jQuery peity -->
<script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
<script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
@endpush

@push('estilos')
<link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
@endpush

@section('conteudo')

<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@component('components/page-title', [
'titulo' => "Configuraçao de retirada de pedidos",
'breadcrumb' => [
'Fretes' => route('comercial.fretes_entregas'),
'Retiradas',
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
            <form action="{{route('comercial.fretes_retiradas.update_frete_retirada')}}" method="post">
                @csrf
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

                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary btnfiltro" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-check-circle"></i> Salvar alterações
                            </button>
                        </div>

                    </div>
                </div>
            </form>


            @livewire('comercial.fretes-retiradas-pesquisa')


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