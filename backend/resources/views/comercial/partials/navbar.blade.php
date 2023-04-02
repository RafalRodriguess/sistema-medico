<div class="navbar-header">
    <a class="navbar-brand" href="javascript:script(0)">
        <!-- Logo icon --><b>
            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
            <!-- Dark Logo icon -->
            {{-- <img src="{{ asset('material/assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" /> --}}
            <!-- Light Logo icon -->
            <img src="{{ asset('material/assets/images/logo-branca-healt.png') }}" alt="homepage" class="light-logo" style="height: 35px"/>
        </b>
        <!--End Logo icon -->
        <!-- Logo text --><span>
            <!-- dark Logo text -->
            {{-- <img src="{{ asset('material/assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" /> --}}
            <!-- Light Logo text -->
            <img src="{{ asset('material/assets/images/logo-texto-branca-healt.png') }}" class="light-logo" alt="homepage" style="height: 35px"/></span>
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
                @if (!empty($comercial_sessao))
                    {{ $comercial_sessao->nome_fantasia }}
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
        @if (count($comerciais_usuario) > 1)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-book-open"></i>
                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                    <ul>
                        <li>
                            <div class="drop-title">Trocar de Comercial</div>
                        </li>
                        <li>
                            <div class="message-center">
                                <!-- Message -->
                                @foreach ($comerciais_usuario as $comercial_usuario)
                                    <a href="{{ route('comercial.eu.escolher_comercial', [$comercial_usuario]) }}">
                                        @if ($comercial_usuario->logo)
                                            <img src="{{\Storage::cloud()->url($comercial_usuario->logo) }}" alt="user" style="width: 45px;" />
                                        @else
                                            <div class="btn btn-danger btn-circle"><i class="ti-import"></i></div>
                                        @endif
                                        <div class="mail-contnet">
                                            <h5>{{$comercial_usuario->nome_fantasia}}</h5>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                        <li>
                            <a class="nav-link text-center" href="{{route('comercial.eu.comerciais')}}"> <strong>Ver todos os comerciais</strong> <i class="fa fa-angle-right"></i> </a>
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
                                    <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new
                                        admin!</span> <span class="time">9:30 AM</span>
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
                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span>
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
                @if (request()->user('comercial')->foto)
                    {{\Storage::cloud()->url(request()->user('comercial')->foto, \Carbon\Carbon::now()->addMinute()) }}
                @else
                    {{ asset('material/assets/images/users/user-profile.png') }}
                @endif
                " alt="user"
                    class="profile-pic" /></a>
            <div class="dropdown-menu dropdown-menu-right scale-up">
                <ul class="dropdown-user">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="
                            @if (request()->user('comercial')->foto)
                                {{\Storage::cloud()->url(request()->user('comercial')->foto, \Carbon\Carbon::now()->addMinute()) }}
                            @else
                                {{ asset('material/assets/images/users/user-profile.png') }}
                            @endif" alt="user"></div>
                            <div class="u-text">
                                <h4>{{ Str::limit(request()->user('comercial')->nome, 15)}}</h4>
                                <p class="text-muted">{{ Str::limit(request()->user('comercial')->email, 15)}}</p><a href="{{ route('comercial.comerciais_usuarios.edit', [request()->user('comercial')]) }}" class="btn btn-rounded btn-danger btn-sm">Ver
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
                        <a href="{{ route('comercial.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"> Logout </i>
                        </a>
                        <form id="logout-form" action="{{ route('comercial.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</div>
