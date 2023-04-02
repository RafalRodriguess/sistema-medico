
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
    <title>Gestor Hospitalar - Admin</title>
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
    <livewire:styles>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!--Menu sidebar -->


    <style>
        @media screen {
            #hidden-printable {
                display: none;
            }
        }
        @media print {
            #main-wrapper {
                display: none !important;
            }
            #hidden-printable {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
            }
        }
    </style>
    @stack('estilos')
</head>

<body class="fix-header fix-sidebar card-no-border">
<div class="loading-off" id="loading"><div class="loadericon"></div></div>

<style>


</style>
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
    <div id="main-wrapper" class="totem-layout">
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container">
                @yield('conteudo')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © 2022 Asa saúde
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <div id="hidden-printable"></div>
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
    <script src="{{ asset('material/assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/maskmoney/jquery.maskMoney.js') }}"></script>



    <script src="{{ asset('material/assets/plugins/toast-master/js/jquery.toast.js') }}"></script>

    <!-- Cropper -->
    <script src="{{ asset('material/assets/plugins/cropperjs/dist/cropper.min.js') }}"></script>

    <script src="{{ asset('material/js/meio.mask.js') }}"></script>

    <script src="{{ asset('material/assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>

    @stack('scripts')

    <script>
        $(".select2").select2();
        $('input[type="text"]').setMask();
        $(function() {
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

