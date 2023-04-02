<div class="navbar-header">
    <a class="navbar-brand" href="javascript:script(0)">
        <!-- Logo icon --><b>
            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
            <!-- Dark Logo icon -->
            {{-- <img src="{{ asset('material/assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" /> --}}
            <!-- Light Logo icon -->
            <img src="{{ asset('material/assets/images/fav-header.png') }}" alt="homepage" class="light-logo" style="height: 35px;"/>
            {{-- <img src="{{ asset('material/assets/images/fav-header-event.png') }}" alt="homepage" class="light-logo" style="height: 35px;"/> --}}
        </b>
        <!--End Logo icon -->
        <!-- Logo text --><span>
            <!-- dark Logo text -->
            {{-- <img src="{{ asset('material/assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" /> --}}
            <!-- Light Logo text -->
            <img src="{{ asset('material/assets/images/asa-white.png') }}" class="light-logo" alt="homepage" style="height: 18px;"/></span>
    </a>
</div>
<!-- ============================================================== -->
<!-- End Logo -->
<!-- ============================================================== -->
<div class="navbar-collapse">
    <!-- ============================================================== -->
    <!-- toggle and nav items -->
    <!-- ============================================================== -->
    <ul class="navbar-nav mr-auto mt-md-0">
        <!-- This is  -->
        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
        <!-- ============================================================== -->
        <!-- Search -->
        <!-- ============================================================== -->
        <li class="nav-item hidden-sm-down search-box">
            <span class="nav-link hidden-sm-down text-muted waves-effect waves-dark" style="color: white!important">
                @if (!empty($instituicao_sessao))
                    {{ $instituicao_sessao->nome }}
                @endif
            </span>
            {{-- <a class="nav-link hidden-sm-down text-muted waves-effect waves-dark"
                href="javascript:void(0)"><i class="ti-search"></i></a>
            <form class="app-search">
                <input type="text" class="form-control" placeholder="Pesquise e aperte enter..."> <a class="srh-btn"><i
                        class="ti-close"></i></a> </form> --}}
        </li>
        <!-- ============================================================== -->
        <!-- Messages -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- End Messages -->
        <!-- ============================================================== -->
    </ul>
    <!-- ============================================================== -->
    <!-- User profile and search -->
    <!-- ============================================================== -->
    <ul class="navbar-nav my-lg-0">
        <!-- ============================================================== -->
        <!-- Comment -->
        <!-- ============================================================== -->

        <!-- INTEGRAÇÃO ASAPLAN -->
        @if (!empty($instituicao_sessao) && $instituicao_sessao->integracao_asaplan == 1)
            {{-- @can('habilidade_instituicao_sessao', 'sincronizar_pacientes_asaplan') --}}
            {{-- <li class="nav-item dropdown" id="chat-notifications-dropdown">
                <a class="nav-link  text-muted text-muted waves-effect waves-dark" href="javascript:void()"
                onclick="sincronizar_pacientes_asaplan()" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-account-switch"></i>
                    <div class="notify" style="display: none"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
            
            </li> --}}
            {{-- @endcan --}}
        @endif
        <!-- FIM INTEGRAÇÃO ASAPLAN -->

        @can('habilidade_instituicao_sessao', 'utilizar_chat')
            <li class="nav-item dropdown" id="chat-notifications-dropdown">
                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
                    <div class="notify" style="display: none"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
                <div id="chat-messages-dropdown" class="dropdown-menu dropdown-menu-right mailbox scale-up">
                    <ul>
                        <li>
                            <div class="drop-title">Últimas mensagens</div>
                        </li>
                        <li class="position-relative">
                            <div class="message-center">
                            </div>
                            <div class="loading-container">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="nav-link text-center" target="_blank" href="{{route('instituicao.chat.index')}}"> <strong>Ir para o chat</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endcan
        @if (count($instituicao_usuario) > 1)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-book-open"></i>
                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                    <ul>
                        <li>
                            <div class="drop-title">Trocar de Instituição</div>
                        </li>
                        <li>
                            <div class="message-center">
                                <!-- Message -->
                                @foreach ($instituicao_usuario as $instituicao_usuario)
                                    <a href="{{ route('instituicao.eu.escolher_instituicao', [$instituicao_usuario]) }}">

                                        @if ($instituicao_usuario->imagem)
                                            <img src="{{\Storage::cloud()->url($instituicao_usuario->imagem) }}" alt="user" style="width: 45px;" />
                                        @else
                                            <div class="btn btn-danger btn-circle"><i class="ti-import"></i></div>
                                        @endif


                                        <div class="mail-contnet">
                                            <h5>{{$instituicao_usuario->nome}}</h5>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                        <li>
                            <a class="nav-link text-center" href="{{route('instituicao.eu.instituicoes')}}"> <strong>Ver todas as instituições</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        {{-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                <ul>
                    <li>
                        <div class="drop-title">Notifications</div>
                    </li>
                    <li>
                        <div class="message-center">
                            <!-- Message -->
                            <a href="#">
                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                <div class="mail-contnet">
                                    <h5>Luanch instituicao</h5> <span class="mail-desc">Just see the my new
                                        instituicao!</span> <span class="time">9:30 AM</span>
                                </div>
                            </a>
                            <!-- Message -->
                            <a href="#">
                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                <div class="mail-contnet">
                                    <h5>Event today</h5> <span class="mail-desc">Just a reminder that
                                        you have event</span> <span class="time">9:10 AM</span>
                                </div>
                            </a>
                            <!-- Message -->
                            <a href="#">
                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                <div class="mail-contnet">
                                    <h5>Settings</h5> <span class="mail-desc">You can customize this
                                        template as you want</span> <span class="time">9:08 AM</span>
                                </div>
                            </a>
                            <!-- Message -->
                            <a href="#">
                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                <div class="mail-contnet">
                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my instituicao!</span>
                                    <span class="time">9:02 AM</span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all
                                notifications</strong> <i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
            </div>
        </li> --}}
        <!-- ============================================================== -->
        <!-- End Comment -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Messages -->
        <!-- ============================================================== -->
        <li class="nav-item dropdown">

            <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">


            </div>
        </li>
        <!-- ============================================================== -->
        <!-- End Messages -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Profile -->
        <!-- ============================================================== -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><img src="
                @if (request()->user('instituicao')->foto)
                    {{\Storage::cloud()->url(request()->user('instituicao')->foto) }}
                @else
                    {{ asset('material/assets/images/client_default.png') }}
                @endif
                " alt="user"
                    class="profile-pic" /></a>
            <div class="dropdown-menu dropdown-menu-right scale-up">
                <ul class="dropdown-user">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="
                            @if (request()->user('instituicao')->foto)
                                {{\Storage::cloud()->url(request()->user('instituicao')->foto, \Carbon\Carbon::now()->addMinute()) }}
                            @else
                                {{ asset('material/assets/images/client_default.png') }}
                            @endif" alt="user"></div>
                            <div class="u-text">
                                <h4>{{ Str::limit(request()->user('instituicao')->nome, 15)}}</h4>
                                <p class="text-muted">{{ Str::limit(request()->user('instituicao')->email, 15)}}</p><a href="{{ route('instituicao.instituicoes_usuarios.edit', [request()->user('instituicao')]) }}" class="btn btn-rounded btn-danger btn-sm">Ver
                                    Perfil</a>
                            </div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    {{-- <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                    <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                    <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                    <li role="separator" class="divider"></li> --}}
                    <li>
                        <a href="{{ route('instituicao.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"> Sair </i>
                        </a>
                        <form id="logout-form" action="{{ route('instituicao.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</div>

@push('scripts')
    <script>

    function sincronizar_pacientes_asaplan(){

        Swal.fire({   
                title: "Deseja sincronizar pacientes do Plano?",   
                text: "Esta ação busca novos titulares e beneficiários do plano ou atualizações feitas nos mesmos de todas as filiais da instituição.",   
                icon: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, confirmar!",   
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {   
                if (result.value) {     
                    // $(e.currentTarget).parents('form').submit();

                    $.ajax("{{ route('sincronizar.pacientesAsaplan') }}", {
                        type: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            // console.log(result)
                            if (result == "sincronizado") {
                                alert('Paciente(s) atualizado(s) com sucesso. ');
                            } else {
                                alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833 ');
                            }

                            // setTimeout(function() {
                            //     location.reload(1);
                            // }, 2000);
                        }
                    });

                } 
            });

        /*
                if (confirm('Confirmar transmissão de guias selecionada(s)?')) {
                var id = '';
                var obj = $(".checks_agendamentos:checked");
                if (obj.length > 0) {
                    obj.each(function() {
                        id += ';' + $(this).val();
                    });
                    id = id.substr(1);

                    $.ajax("{{ route('instituicao.faturamento.adicionarGuias') }}", {
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            console.log(result)
                            if (result == 'erro') {

                                alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833 ')
                            } else {
          
                                alert('Guia(s) enviadas!", "Acompanhe o processamento neste mesmo módulo. ')
                            }

                            // setTimeout(function() {
                            //     location.reload(1);
                            // }, 2000);
                        }
                    });
                } else {
                    alert('Selecione pelo menos 1 atendimento!')
                }
            }

            */
            }

    </script>
@endpush
