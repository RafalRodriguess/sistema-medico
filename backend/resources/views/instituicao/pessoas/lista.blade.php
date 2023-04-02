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
          
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
            <div class="col-md-7 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Pacientes</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Administração</a></li>
                    <li class="breadcrumb-item active">Pacientes</li>
                </ol>
            </div>

            <!-- INTEGRAÇÃO ASAPLAN LEGENDA SITUAÇÕES DO PLANO -->
            @if (!empty($instituicao) && $instituicao->integracao_asaplan == 1)

            <div class="col-md-5 col-8 align-self-center">
                <button type="button" class="btn btn-success btn-rounded" style="background-color: #b2f2b3;border-color: #b2f2b3;"> Ativos</button>
                <button type="button" class="btn btn-success btn-rounded" style="background-color: #cdd2f7;border-color: #cdd2f7;"> Suspensos</button>
                <button type="button" class="btn btn-success btn-rounded" style="background-color: #f1c1c6;border-color: #f1c1c6;"> Cancelados</button>
                <button type="button" class="btn btn-success btn-rounded" style="background-color: #fff;border-color: #67757c;color:#67757c;"> Não Associado</button>
            </div>

            @endif
            <!-- FIM INTEGRAÇÃO ASAPLAN -->
            
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <!-- Column -->
                <div class="card lista-pessoas">
                    @livewire('instituicao.pessoas-pesquisa')
                </div>
                <!-- Column -->
                
                <!-- Column -->
                
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
                     
@endsection


@push('scripts')
    <script>
        $('.lista-pessoas').on('click','.btn-excluir-registro-pessoa', function(e) {
            e.preventDefault();

            Swal.fire({   
                title: "Confirmar exclusão?",   
                text: "Ao confirmar você estará excluindo o registro permanente!",   
                icon: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, confirmar!",   
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {   
                if (result.value) {     
                    $(e.currentTarget).parents('form').submit();
                } 
            });
        });
    </script>
@endpush