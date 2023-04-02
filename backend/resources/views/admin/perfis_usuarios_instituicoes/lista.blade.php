@extends('admin.layout')


@push('scripts')
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
@endpush

@push('estilos')
    <link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
@endpush

@section('conteudo')
          

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Perfis de Usuários da Instituição</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Administração</a></li>
                            <li class="breadcrumb-item active">Perfis de Usuários da Instituição</li>
                        </ol>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @livewire('admin.perfis-usuarios-instituicoes-pesquisa')
                        </div>
                    </div>
                </div>                     
@endsection