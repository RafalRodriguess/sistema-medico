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
        <title>(Admin) Asa Saúde - Acesso ao Sistema</title>
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('material/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ asset('material/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('material/assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">
        <link href="{{ asset('material/assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

        <!-- You can change the theme colors from here -->
        <link href="{{ asset('material/css/colors/blue.css') }}" id="theme" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body class="m-2">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gerar movimentação de contas</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Relatorio</li>
                    <li class="breadcrumb-item">Fluxo de caixa</li>
                    <li class="breadcrumb-item active">Movimentação de contas</li>
                </ol>
            </div>
        </div>

        <form id="formGeraMovimento">
            @csrf
            
            <div class="card-body">                            
                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has('tipo_movimentacao')) has-danger @endif">
                        <label class="form-control-label">Tipo movimentação *</label>
                        <select class="form-control select2 @if ($errors->has('tipo_movimentacao')) form-control-danger @endif" name="tipo_movimentacao" id="tipo_movimentacao" style="width: 100%">
                            @foreach ($tipo_movimentacao as $item)
                                <option value="{{$item}}"  @if (old('tipo_movimentacao', 'transferencia') == $item)
                                    selected="selected"
                                @endif >{{App\Movimentacao::natureza_para_texto($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_movimentacao'))
                            <div class="form-control-feedback">{{ $errors->first('tipo_movimentacao') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group col-md-3 @if($errors->has('data')) has-danger @endif">
                        <label class="form-control-label">Data *</label>
                        <input type="date" name="data" value="{{ old('data', date("Y-m-d")) }}"
                        class="form-control @if($errors->has('data')) form-control-danger @endif">
                        @if($errors->has('data'))
                            <div class="form-control-feedback">{{ $errors->first('data') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('conta_id_origem')) has-danger @endif">
                        <label class="form-control-label">Contas origem *</span></label>
                        <input type="hidden" value="{{$conta_selecionada}}" name="conta_id_origem" id="conta_id_origem">
                        <select class="form-control select2 @if ($errors->has('conta_id_origem')) form-control-danger @endif" style="width: 100%" disabled>
                            @foreach ($contas as $conta)
                                <option value="{{$conta->id}}"  @if (old('conta_id_origem', $conta_selecionada) == $conta->id) selected="selected" @endif >{{$conta->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id_origem'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id_origem') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group col-md-3 @if($errors->has('conta_id_destino')) has-danger @endif">
                        <label class="form-control-label">Contas destino *</span></label>
                        <select class="form-control select2 @if ($errors->has('conta_id_destino')) form-control-danger @endif" name="conta_id_destino" id="conta_id_destino" style="width: 100%">
                            <option value="">Selecione uma conta</option>
                            @foreach ($contas as $conta)
                                <option value="{{$conta->id}}"  @if (old('conta_id_destino') == $conta->id)
                                    selected="selected"
                                @endif >{{$conta->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id_destino'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id_destino') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label class="form-control-label">Saldo</label>
                        <input type="text" alt="decimal" id="saldo" disabled value="{{ number_format($saldo, 2) }}"
                        class="form-control valor">
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('valor')) has-danger @endif">
                        <label class="form-control-label">Valor *</span></label>
                        <input type="text" alt="decimal" id="valor" name="valor" value="{{old('valor', (!empty($valor)) ? number_format(str_replace(['.',','],['','.'],$valor), 2) : number_format($saldo, 2) )}}"
                        class="form-control valor @if($errors->has('valor')) form-control-danger @endif">
                        @if($errors->has('valor'))
                            <div class="form-control-feedback">{{ $errors->first('valor') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 dif">
                        <label class="form-control-label">Diferença saldo / valor</label>
                        <input type="text" alt="decimal" id="dif" disabled class="form-control valor">
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-control-label">Observação</span></label>
                        <textarea class="form-control" name="obs" id="obs" cols="5" rows="5">{{old('obs')}}</textarea>
                    </div>
                </div>     
            </div>
            <button class="btn btn-success waves-effect waves-light m-r-10 salvaMovimento">Confirmar</button>
        </form>

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
        <script src="{{ asset('material/js/sidebarmenu.js') }}"></script>
        <!--stickey kit -->
        <script src="{{ asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!--Custom JavaScript -->
        <script src="{{ asset('material/js/custom.min.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/ladda/spin.min.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/ladda/ladda.min.js') }}"></script>

        <script src="{{ asset('material/js/meio.mask.js') }}"></script>
        <!-- ============================================================== -->
        <!-- Style switcher -->
        <!-- ============================================================== -->
        <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    </body>
</html>

<script>
    $(document).ready(function(){
        $('.valor').setMask();

        dif();        

        $("#valor").on("change", function(){
            saldo = ($("#saldo").val()).replace('.','')
            saldo = saldo.replace(',','.')
            
            valor = ($("#valor").val()).replace('.','')
            valor = valor.replace(',','.')

            dif = parseFloat(saldo) - parseFloat(valor);

            if(dif < 0){
                $("#dif").css("color","#8b0000")
                $(".salvaMovimento").attr("disabled", true)
                $(".salvaMovimento").attr("title", "Conta sem saldo suficiente para movimentação!")
                $.toast({
                    heading: 'Erro',
                    text: "Conta sem saldo suficiente para movimentação!",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });
            }else{
                $("#dif").css("color","#006400")
                $(".salvaMovimento").attr("disabled", false)
            }
            
            $("#dif").val(dif.toFixed(2));
            // console.log($("#saldo").val() - $("#valor").val());
        });

        $(".salvaMovimento").on("click", function(e){
            e.preventDefault();
            // e.stopPropagation();
            dif = $("#saldo").val() - $("#valor").val();
            if(dif < 0){
                $.toast({
                    heading: 'Erro',
                    text: "Conta sem saldo suficiente para movimentação!",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });
            }else{
                salvaMovimento();
            }
            
            
            
        });
    })

    function dif(){
        saldo = ($("#saldo").val()).replace('.','');
        saldo = saldo.replace(',','.');
        
        valor = ($("#valor").val()).replace('.','');
        valor = valor.replace(',','.');

        dif = parseFloat(saldo) - parseFloat(valor);

        $("#dif").val(dif.toFixed(2));
    }

    function salvaMovimento(){
        
        var dados = new FormData($('#formGeraMovimento')[0]);
        console.log(dados)
        $.ajax("{{route('instituicao.relatoriosFluxoCaixa.salvarMovimentacao')}}", {
            method: "POST",
            data: dados,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (result) {
                if(result.icon === 'error'){
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                }else{
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                    // $('#modalMovimentacao').modal('hide');
                    
                    window.setTimeout("window.close()",3000);
                    
                }
                
                
                // $(".table-responsive").html(result);
                // $(".imprimir").css('display', 'block')
                // ativarClass();
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(result){
                if(result.responseJSON.errors){
                    Object.keys(result.responseJSON.errors).forEach(function(key) {
                        $.toast({
                            heading: 'Erro',
                            text: result.responseJSON.errors[key][0],
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