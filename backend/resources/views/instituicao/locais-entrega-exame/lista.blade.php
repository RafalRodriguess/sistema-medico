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
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    @component('components/page-title', [
        'titulo' => 'Locais para entrega de exames',
        'breadcrumb' => [
            'Locais para entrega de exames',
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
            <!-- Column -->
            <div class='card'>
                @livewire('instituicao.locais-entrega-exames-pesquisa')
            </div>
            <!-- Column -->

            <!-- Column -->

        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
@endsection
