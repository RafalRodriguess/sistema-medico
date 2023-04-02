@extends('instituicao.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}'></script>
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}'></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src='{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}'></script>
@endpush

@push('estilos')
    <link href='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}' rel='stylesheet'>
@endpush

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Motivos para baixa de estoque",
        'breadcrumb' => [
            'Motivos para baixa de estoque'
        ],
    ])
    @endcomponent
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class='row'>
            <div class='col-12'>
                <div class='card'>
                    @livewire('instituicao.motivos-baixa-pesquisa')
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
@endsection
