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
                        <div class="col-md-5 col-8 align-self-center">
                            <h3 class="text-themecolor m-b-0 m-t-0">Instituição</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('instituicao.pacientes')}}">Pacientes</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $paciente->nome }}</a></li>
                                {{-- <li class="breadcrumb-item active">Comerciais</li> --}}
                            </ol>
                        </div>

                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Dados</h4>
                            <form class="form-material m-t-15 row">
                                <div class="form-group col-md-12 m-t-20">
                                    <input type="text" class="form-control form-control-line" value="{{$paciente->nome}}" readonly>
                                </div>
                                <div class="form-group col-md-12 m-t-20">
                                    <input type="text" class="form-control form-control-line" value="{{$paciente->telefone}}" readonly>
                                </div>
                                <div class="form-group col-md-12 m-t-20">
                                    <input type="text" class="form-control form-control-line" value="{{ date('d/m/Y', strtotime($paciente->data_nascimento))}}" readonly>
                                </div>

                                <div class="form-group col-md-12 m-t-10">
                                    <h4 class="card-title">Endereços</h4>
                                </div>
                                @foreach ($paciente->usuarioEnderecos as $item)
                                    <div class="form-group col-md-12 m-t-20">
                                    {{-- <input type="text" class="form-control form-control-line" value="{{$item->rua}}, {{$item->numero}} - {{$item->bairro}} ({{$item->cidade}}/{{$item->estado}})" readonly>  --}}
                                        <textarea class="form-control" rows="4" readonly>{{$item->rua}}, {{$item->numero}} - {{$item->bairro}} ({{$item->cidade}}/{{$item->estado}})
@if ($item->complemento)Complemento: {{$item->complemento}}@endif

@if ($item->referencia)Referência: {{$item->referencia}}@endif
                                        </textarea>
                                    </div>

                                @endforeach

                                <div class="form-group text-right col-md-12">
                                    <a href="{{ route('instituicao.pacientes') }}">
                                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                                    </a>
                                </div>

                            </form>
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
