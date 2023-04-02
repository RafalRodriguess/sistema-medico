@extends('layouts/material')


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
                        <div class="col-md-5 col-8 align-self-center">
                            <h3 class="text-themecolor m-b-0 m-t-0">Saídas</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Veículos</a></li>
                                <li class="breadcrumb-item active">Saídas</li>
                            </ol>
                        </div>
                        
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
                            <div class="card">
                                <div class="card-body">
                                   
                                    <form action="javascript:void(0)" id="FormTitular">
                                        <div class="row">
                                              <div class="col-md-10">
                                                <div class="form-group" style="margin-bottom: 0px !important;">
                                                     
                                                      <input type="text" id="filtro" name="filtro" class="form-control" placeholder="Pesquise por nome...">
                                                    
                                                     
                                                </div>
                                              </div>
                                                                                  <div class="col-md-2">
                                                <div class="form-group" style="margin-bottom: 0px !important;">
                                                     <a href="{{ route('veiculos_saidas.create') }}">
                                                      <button type="button" class="btn waves-effect waves-light btn-block btn-info">Nova saída</button>
                                                     </a>
                                                </div>
                                             </div>
                                                                         </div>
                                      </form>

                                      <hr>

                                    <table id="tabela" class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                                        <thead>
                                            <tr>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Marca</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Modelo</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Cliente</th>
                                                
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($veiculos as $veiculo)
                                                <tr>
                                                    <td class="title"><a href="javascript:void(0)">{{ $veiculo->id }}</a></td>
                                                    <td>{{ $veiculo->tipo }}</td>
                                                    <td>{{ $veiculo->marca->descricao }}</td>
                                                    <td>{{ $veiculo->modelo->descricao }}</td>
                                                    <td>{{ $veiculo->cliente->nome }}</td>
                                                    <td>

                                                            <a href="{{ route('veiculos.edit', [$veiculo]) }}">
                                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="ti-pencil-alt"></i>
                                                                    </button>
                                                            </a>

                                                            <a href="{{ route('veiculos.edit', [$veiculo]) }}" data-toggle="tooltip" data-placement="top" data-original-title="Imprimir check list">
                                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="ti-check-box"></i>
                                                                    </button>
                                                            </a>

                                                            <a href="{{ route('veiculos.edit', [$veiculo]) }}" data-toggle="tooltip" data-placement="top" data-original-title="Imprimir contrato">
                                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" >
                                                                            <i class="ti-write"></i>
                                                                    </button>
                                                            </a>
                                                    
                                                   
                                                        
                                                            <form action="{{ route('veiculos.destroy', [$veiculo]) }}" method="post" class="d-inline form-excluir-registro">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false">
                                                                        <i class="ti-trash"></i>
                                                                </button>
                                                            </form>
                                                    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    {{ $veiculos->links() }}
                                                </td>
                                            </tr> 
                                        </tfoot>
                                    </table>
                                </div>
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
                    <div class="right-sidebar">
                        <div class="slimscrollright">
                            <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                            <div class="r-panel-body">
                                <ul id="themecolors" class="m-t-20">
                                    <li><b>With Light sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                    <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                    <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                    <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                    <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                    <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                    <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                    <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                    <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                    <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                    <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                    <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                                </ul>
                                
                                
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Right sidebar -->
                    <!-- ============================================================== -->
                     
@endsection

@push("scripts")
<script>
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };


    $(function () {

        $("#filtro").on("input", debounce(function () {
            const query = $.param({ pesquisa: $("#filtro").val() });
            $("#tabela").load(`{{ route('veiculos_saidas.index') }}?${query} #tabela`);
        }, 300))

    });
</script>
@endpush