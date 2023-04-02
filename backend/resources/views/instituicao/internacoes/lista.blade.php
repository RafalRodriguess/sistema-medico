@extends('instituicao.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}'></script>
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}'></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src='{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}'></script>
    <script src='{{ asset('calendar/dist/bundle.js') }}'></script>
@endpush

@push('estilos')
    <link href='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}' rel='stylesheet'>
    <link href='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}' rel='stylesheet'>
    <link href='{{ asset('calendar/dist/css/theme-basic.css') }}' rel='stylesheet'>
    <link href='{{ asset('calendar/dist/css/theme-glass.css') }}' rel='stylesheet'>
@endpush

@section('conteudo')
          
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class='row page-titles'>
                        <div class='col-md-5 col-8 align-self-center'>
                            <h3 class='text-themecolor m-b-0 m-t-0'>Internações</h3>
                            <ol class='breadcrumb'>
                                <li class='breadcrumb-item'><a href='javascript:void(0)'>Internações</a></li>
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
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link show active" data-toggle="tab" href="#tab_internacoes" role="tab" >Internações</a></li>
                                <li class="nav-item" id="avaliacao-click"> <a class="nav-link show " data-toggle="tab" href="#tab_avaliacoes" role="tab" >Avaliações</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="tab_internacoes" role="tabpanel">
                                    <div class='card'>
                                        @livewire('instituicao.internacoes-pesquisa')
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_avaliacoes" role="tabpanel">
                                    <div class="card" id="card-avaliacoes">
                                        @livewire('instituicao.avalicoes-pesquisa')
                                    </div>
                                </div>  
                            </div>                          
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End PAge Content -->
                    <!-- ============================================================== -->
                     
@endsection

@push('scripts')
    <script>
        function  vizualisarAvaliacao(element){
            var descricao = $(element).data('descricao');
            var id = $(element).data('id');
            $("#avaliacaoText").html(descricao)
            
            if($(element).data('atendido') == '1'){
                $("#atenderAvaliacao").css('display', 'none')
            }else{
                $("#atenderAvaliacao").css('display', 'block')
                $("#atenderAvaliacao").data('id', id)
            }
            
            $('#modalDescricaoAvaliacao').modal('show')
        
        }

        function atenderAvaliacao(id){            

            $.ajax({
                url: "{{ route('instituicao.internacoes.atenderAvaliacao', ['avaliacao' => 'avaliacao_id']) }}".replace('avaliacao_id', id),
                method: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function (response) {

                    $.toast({
                        heading: response.title,
                        text: response.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: response.icon,
                        hideAfter: 3000,
                        stack: 10
                    });

                    if(response.icon=='success'){
                        $('#modalDescricaoAvaliacao').modal('hide')
                        resetPage();
                    }

                },
                error: function (response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        }
    </script>
@endpush