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
                <div class='row page-titles'>
                        <div class='col-md-5 col-8 align-self-center'>
                            <h3 class='text-themecolor m-b-0 m-t-0'>Modelo Atestado</h3>
                            <ol class='breadcrumb'>
                                <li class='breadcrumb-item'><a href='javascript:void(0)'>Modelo Atestado</a></li>
                                {{-- <li class='breadcrumb-item active'>medicamentoss</li> --}}
                            </ol>
                        </div>
                        
                    </div>
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
                                @livewire('instituicao.modelo-atestado-pesquisa')
                            </div>
                            <!-- Column -->
                            
                            <!-- Column -->
                            
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End PAge Content -->
                    <!-- ============================================================== -->
                     
@endsection