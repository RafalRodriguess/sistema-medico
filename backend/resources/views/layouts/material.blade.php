
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('material/assets/images/logo-h.png') }}">
    <title>Asa Saúde - Admin</title>

    {{-- @if(request()->is('instituicao', 'instituicao/*') && !request()->is('instituicao/login') && !request()->is('instituicao/eu', 'instituicao/eu/*'))
    <script src="{{ asset('turbolinks/turbolinks.min.js') }}"></script>
    <meta name="turbolinks-root" content="/instituicao">
    @endif --}}

    @stack('fonts')
    <!-- Bootstrap Core CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.theme.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.css') }}">
    <link href="{{ asset('material/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Select 2 -->
    <link href="{{ asset('material/assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Cropper -->
    <link href="{{ asset('material/assets/plugins/cropperjs/dist/cropper.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- toast CSS -->
    <link href="{{ asset('material/assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/chartist-js/dist/chartist-init.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{ asset('material/assets/plugins/c3-master/c3.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('material/assets/plugins/clockpicker/dist/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('material/assets/plugins/icheck/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/icheck/skins/flat/blue.css') }}" rel="stylesheet">
    <link href="{{ asset('material/css/style.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('material/css/colors/blue.css') }}" id="theme" rel="stylesheet">

    <link href="{{ asset('material/assets/plugins/icheck/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/icheck/skins/flat/blue.css') }}" rel="stylesheet">

    <link href="{{ asset('material/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('material/assets/plugins/dropify/dist/css/dropify.min.css')}}">
    <link href="{{ asset('material/assets/plugins/summernote-novo/summernote.css')}}" rel="stylesheet" />

    <livewire:styles>

    {{-- Estilos personalizados App.css/App.scss --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!--Menu sidebar -->

    <style>
        .loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }
        .loader {
            margin: 60px auto;
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(3,3,3, 0.2);
            border-right: 1.1em solid rgba(3,3,3, 0.2);
            border-bottom: 1.1em solid rgba(3,3,3, 0.2);
            border-left: 1.1em solid #030303;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
            }
            @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
            }
            @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>

    @stack('estilos')
</head>

<body class="fix-header fix-sidebar card-no-border">
<div class="loading-off" id="loading"><div class="loadericon"></div></div>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                @yield('navbar')
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->

            </nav>
        </header>
        <div class="loading" style="position: fixed; z-index: 9999;background: #80808059; position-top: 0; position-left: 0; width: 100%; height: 100%; display: none;">
            <div class="class-loading"></div>
        </div>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                {{-- <div class="user-profile" style="background: url({{ asset('material/assets/images/background/user-info.jpg') }}) no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="{{ asset('material/assets/images/users/profile.png') }}" alt="user" /> </div>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="true">Markarn Doe</a>
                        <div class="dropdown-menu animated flipInY"> <a href="#" class="dropdown-item"><i class="ti-user"></i>
                                My Profile</a> <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                            <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i>
                                Account Setting</a>
                            <div class="dropdown-divider"></div> <a href="login.html" class="dropdown-item"><i class="fa fa-power-off"></i>
                                Logout</a>
                        </div>
                    </div>
                </div> --}}
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    @yield('sidebar-nav')
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                @yield('sidebar-footer')
            </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @yield('right-sidebar')
        <!-- ============================================================== -->
        <!-- End Right Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                @yield('conteudo')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © {{ date('Y') }} Asa Saúde
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <livewire:scripts>

    <script src="{{ asset('material/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/jqueryui/jquery-ui.min.js') }}"></script>
    {{-- <script src="{{ asset('material/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> --}}
    <script src="{{ asset('material/assets/plugins/datepicker/jquery.ui.datepicker-pt-BR.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('material/assets/plugins/popper/popper.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('material/js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('material/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('material/js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('material/assets/plugins/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('material/js/custom.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    {{-- <script src="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script> --}}
    <!-- select2 -->

    <script src="{{ asset('material/assets/plugins/select2/dist/js/select2.js')}}" type="text/javascript"></script>
    <script src="{{ asset('material/assets/plugins/select2/dist/js/i18n/pt-BR.js')}}" type="text/javascript"></script>
    {{-- <script src="{{ asset('material/assets/plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('material/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}" type="text/javascript"></script>
    <script src="{{ asset('material/assets/plugins/dff/dff.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('material/assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script> --}}
    <!--c3 JavaScript -->
    <script src="{{ asset('material/assets/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/c3-master/c3.min.js') }}"></script>
    <!-- Chart JS -->
    {{-- <script src="{{ asset('material/js/dashboard1.js') }}"></script> --}}
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/icheck/icheck.min.js') }}"></script>
    <!-- Sweet-Alert  -->
    {{-- <script src="{{ asset('material/assets/plugins/sweetalert/sweetalert.min.js') }}"></script> --}}
    <script src="{{ asset('material/assets/plugins/sweetalert2new/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/maskmoney/jquery.maskMoney.js') }}"></script>



    <script src="{{ asset('material/assets/plugins/toast-master/js/jquery.toast.js') }}"></script>

    <!-- Cropper -->
    <script src="{{ asset('material/assets/plugins/cropperjs/dist/cropper.min.js') }}"></script>

    <script src="{{ asset('material/js/meio.mask.js') }}"></script>

    <script src="{{ asset('material/assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>

    {{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script> --}}
    <script src="{{ asset('material/js/lodash/lodash.js')}}"></script>
    <script src="{{ asset('material/assets/plugins/summernote-novo/summernote.min.js')}}"></script>

    {{-- APP script --}}
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/htmlTemplate.js') }}"></script>
    @stack('scripts')

    <script>
        $(".select2").select2();
        $('input[type="text"]').setMask();
        document.addEventListener("DOMContentLoaded", () => {
            window.livewire.hook('afterDomUpdate', () => {
                var listaPessoa = $("body").find('.lista-pessoas')
                if(listaPessoa.length > 0){
                    $(".lista-pessoas").find('.tablesaw').tablesaw()
                }
                $('.checkboxAgendamentoPesquisa').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                }).on('ifChanged', function (e) {
                    verificaCheckBoxHorarios()
                });
            })
        });

        $(function() {
            $('.checkboxAgendamentoPesquisa').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            }).on('ifChanged', function (e) {
                verificaCheckBoxHorarios()
            });

            $(".selectfild2").each(function () {
                var $select = $(this);
                if (!$(this).attr('wire:model')) {
                    $select.select2();
                    return;
                }

                var $id = $(this).parents('[wire\\:id]').attr('wire:id');
                $select.select2().on('select2:select', function (e) {
                    window.livewire.find($id).set($(this).attr('wire:model'), e.params.data.id);
                });
            });
            $('.dropify').dropify();

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Você deseja apagar a foto\"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('Arquivo deletado');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Houve erro');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })

        });
    </script>

    @if($mensagem = session('mensagem'))
    {{-- Se transformar aquele withs para array no lugar de string já vai funcionar, pode depois trocar o Swal por toaster ou algo assim --}}
    <script>
        (function () {

            jQuery(function($) {
                $.toast({
                    heading: '{{ $mensagem['title'] ?? $mensagem }}',
                    text: '{{ $mensagem['text'] ?? '' }}',
                    position: '{{ $mensagem['position'] ?? 'top-right' }}',
                    loaderBg:'{{ $mensagem['loaderBg'] ?? '#ff6849' }}',
                    icon: '{{ $mensagem['icon'] ?? 'success' }}',
                    hideAfter: {{ $mensagem['hideAfter'] ?? 3000 }},
                    stack: 10
                });
            })
        }())
    </script>
    @endif
</body>

</html>

