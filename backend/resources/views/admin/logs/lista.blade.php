@extends('admin.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/JSON-Data-Viewer/json-viewer/jquery.json-viewer.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/Beautiful-JSON-Viewer/src/jquery.json-viewer.js') }}"></script>
@endpush

@push('estilos')
    <link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('material/assets/plugins/Beautiful-JSON-Viewer/src/jquery.json-viewer.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('material/assets/plugins/JSON-Data-Viewer/json-viewer/jquery.json-viewer.css') }}" rel="stylesheet">
@endpush

@section('conteudo')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Erros</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Erros</a></li>
                {{-- <li class="breadcrumb-item active">Usuarioss</li> --}}
            </ol>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                @livewire('admin.logs-pesquisa')
            </div>

        </div>
    </div>

@endsection
