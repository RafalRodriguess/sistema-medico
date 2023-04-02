
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('material/assets/images/logo-h.png') }}">
        <title>Asa saude - admin</title>
        <!-- Bootstrap Core CSS -->
        {{-- <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.theme.min.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('material/assets/plugins/jqueryui/jquery-ui.css') }}">
        <link href="{{ asset('material/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <style>
            body{
                /* height: 100vh; */
            }
            .print-table {
                /* min-height: 100vh; */
                /* height: 100%; */
            }

            .content-wrap {
                min-height: 85%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            #footer {
                flex: 0;
            }

            .print-table:last-child {
                page-break-after: avoid;
            }
        </style>
    </head>

    <body>
        <div class="print-table" @if (array_key_exists('impressao', $relatorio->relatorio))
            @if ($relatorio->relatorio['impressao'] != null)
            style="font-size: {{$relatorio->relatorio['impressao']['tamanho_fonte']}}px; padding: {{$relatorio->relatorio['impressao']['margem_cabecalho']}}cm {{$relatorio->relatorio['impressao']['margem_direita']}}cm {{$relatorio->relatorio['impressao']['margem_rodape']}}cm {{$relatorio->relatorio['impressao']['margem_esquerda']}}cm"
            @endif
            @endif>
            <div class="content-wrap">
                <div>
                    @if (array_key_exists('impressao', $relatorio->relatorio))
                        @if ($relatorio->relatorio['impressao'] != null)
                            {!! $relatorio->relatorio['impressao']['cabecalho'] !!}
                        @endif
                    @endif
                </div>
                <div >
                    {{-- <hr style="margin-top: 60px;"> --}}
                    @if ($exibir_titulo_paciente)                    
                        <p style="text-align: center"><b><span>RELATÓRIO</span></b></p>
                        <p>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                            <td style="text-align: left; border-top: 0px; padding: 0px"><b>Paciente: </b>{{$agendamento->pessoa->nome}}</td>
                                            <td style="text-align: right; border-top: 0px; padding: 0px">@if ($exibir_data)<b>Data: </b>{{date("d/m/Y", strtotime($agendamento->data))}} @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </p>
                    @endif
                    <p>
                        <div style="margin-top: 30px;">
                            {!!$relatorio->relatorio['obs']!!}
                        </div>
                    </p>
                </div>   
            </div>   
            
            <div id="footer">
                @if (array_key_exists('impressao', $relatorio->relatorio))
                    @if ($relatorio->relatorio['impressao'] != null)
                        {!! $relatorio->relatorio['impressao']['rodape'] !!}
                    @endif
                @endif
            </div>
        </div>


        <script src="{{ asset('material/assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('material/assets/plugins/jqueryui/jquery-ui.min.js') }}"></script>
        
        <script src="{{ asset('material/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

        <script>
            $(document).ready(function(){
                setTimeout(() => {
                    window.print();
                }, 0);
            })
        </script>
    </body>
</html>
