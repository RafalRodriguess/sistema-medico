@extends('instituicao.layout')


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
    @component('components/page-title',
        [
            'titulo' => "Produtos da baixa de estoque #{$estoqueBaixa->id}",
            'breadcrumb' => [
                'Baixas de estoque' => route('instituicao.estoque_baixa_produtos.index'),
                'Produtos da baixa de estoque',
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
            <div class="card">
                @livewire('instituicao.produtos-baixa-pesquisa', ['estoqueBaixa' => $estoqueBaixa])
            </div>
            <!-- Column -->

            <!-- Column -->

        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->

    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
@endsection
