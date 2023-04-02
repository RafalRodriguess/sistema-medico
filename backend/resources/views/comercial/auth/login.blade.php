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
    <title>Tectotum - Acesso ao Sistema</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('material/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('material/css/style.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('material/assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

    <link href="{{ asset('material/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body>
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
    <section id="wrapper">
        <div class="login-register" style="background: #f5f5f5;">


        		      <div class="row" style="padding-bottom: 25px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                <img src="{{ asset('material/assets/images/logo.png') }}" width="200" >
                            </div>
                        </div>

            <div class="login-box card">

                <div class="card-body">

                    <form class="form-horizontal form-material" id="loginform" method="post" action="{{ route('comercial.login') }}">
                        @csrf

                        @error('cpf')
                            <span class="" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        @error('password')
                            <span class="" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <h3 class="box-title m-b-20">Acessar Sistema</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" alt="cpf" name="cpf" placeholder="Usuário"> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" name="password" placeholder="Senha"> </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex no-block align-items-center">
                                <div class="checkbox checkbox-primary p-t-0">
                                    <input id="checkbox-signup" type="checkbox" name="remember">
                                    <label for="checkbox-signup"> Lembrar-me </label>
                                </div>
                                <div class="ml-auto">
                                    <a href="javascript:void(0)" class="popper_lost_password text-muted"><i class="fa fa-lock m-r-5"></i> Esqueceu a senha?</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Entrar</button>
                            </div>
                        </div>

                        {{-- <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                Não é cadastrado? <a href="pages-register.html" class="text-info m-l-5"><b>Cadastre-se</b></a>
                            </div>
                        </div> --}}

                    </form>

                </div>
            </div>
        </div>

        <div class="modal inmodal" id="modal-reset" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recuperar Senha</h3>
                                <p class="text-muted">Digite seu e-mail e as instruções serão enviadas para você! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" id="reset_email" required="" placeholder="Email"> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button type='submit' id="enviar" class="ladda-button btn btn-primary" data-style="zoom-in" type="button">Enviar link de recuperação</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('material/assets/plugins/popper/popper.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('material/js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('material/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('material/assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/ladda/spin.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/ladda/ladda.min.js') }}"></script>
    <script src="{{ asset('material/js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('material/js/custom.min.js') }}"></script>

    <script src="{{ asset('material/js/meio.mask.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

    <script>
        $(function(){
            $('input[type="text"]').setMask();

            $('.popper_lost_password').on('click', function () {
                $('#modal-reset').modal('show');
            });

            $('#enviar').on('click',function(){
                var l = Ladda.create(this);
                if($('#reset_email').val().length>0){
                    $.ajax({
                    url: "{{route('comercial.send_token_recover_password')}}",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        email: $('#reset_email').val(),
                        '_token': '{{csrf_token()}}',
                    },
                    beforeSend: function(){
                        l.start();
                        setTimeout(function(){
                            l.stop();
                        },6000)
                    },
                    success: function (data) {
                        l.stop();
                        $.toast({
                            heading: data.title,
                            text: data.msg,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon:  data.status,
                            hideAfter: 9000,
                            stack: 10
                        });
                        $('#modal-reset').modal('hide');
                    },
                    });
                }
                return false;
            });
        })
    </script>

</body>

</html>
