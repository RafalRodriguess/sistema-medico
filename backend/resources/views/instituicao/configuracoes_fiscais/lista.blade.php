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
            <h3 class="text-themecolor m-b-0 m-t-0">Configurações fiscais</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Configurações fiscais</a></li>
                {{-- <li class="breadcrumb-item active">medicamentoss</li> --}}
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
                    <h4>{{$configuracao->cod_servico_municipal}} - {{$configuracao->descricao}}</h4>
                    <small>{{App\ConfiguracaoFiscal::regime_texto($configuracao->regime)}}</small>
                    <hr>

                    <label class="col-sm-3">Aliquota IIS: {{$configuracao->aliquota_iss}}%</label>
                    <label class="col-sm-3">Percentual PIS: {{$configuracao->p_pis}}%</label>
                    <label class="col-sm-3">Percentual COFINs: {{$configuracao->p_cofins}}%</label>
                    <label class="col-sm-3">Percentual INSS: {{$configuracao->p_inss}}%</label>
                    <label class="col-sm-3">Percentual IR: {{$configuracao->p_ir}}%</label>

                    <hr>
                    <div class="form-groupn text-right pb-2">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 ver_caract"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Ver Características da Prefeitura" value="{{$configuracao->instituicao_id}}">
                            <i class="mdi mdi-clipboard"></i>
                        </button>
                        
                        @can('habilidade_instituicao_sessao', 'cadastro_empresa_enotas')
                            <button type="button" class="btn btn-primary waves-effect waves-light m-r-10 enviarEmpresa"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Enviar informações da empresa para servidor de NFe">
                                <i class="mdi mdi-cloud-upload"></i>
                            </button>
                        @endcan
                        
                        @can('habilidade_instituicao_sessao', 'editar_configuracao_fiscal')
                            <a href="{{ route('instituicao.configuracaoFiscal.edit', [$configuracao]) }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i> Editar
                                </button>
                            </a>
                        @endcan                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal no_print" id="caract_prefeitura" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 1200px;">
            <div class="modal-content" style="background: #f8fafb;"></div>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
                     
@endsection

@push('scripts')
    <script>
        $(".enviarEmpresa").on("click", function(){
            $.ajax("{{ route('instituicao.notasFiscais.cadastrarEmpresa') }}", {
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result){
                    if(result.icon == "error"){
                        Object.keys(result.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: result.errors[key].mensagem,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });
                        });
                    }else{
                        $.toast({
                            heading: result.title,
                            text: result.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: result.icon,
                            hideAfter: 9000,
                            stack: 10
                        });
                    }

                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });
        })

        $('.ver_caract').on('click', function(){
            instituicao_id = $(this).val();

            $.ajax("{{ route('instituicao.notasFiscais.perfilPrefeitura', ['instituicao' => 'instituicao_id']) }}".replace('instituicao_id', instituicao_id), {
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result){
                    console.log(result);
                    $('#caract_prefeitura .modal-content').html(result);
                    $("#caract_prefeitura").modal('show');
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });
        })
    </script>
@endpush