
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
        <div class="print-table" @if (array_key_exists('impressao', $prontuario->prontuario))
                @if ($prontuario->prontuario['impressao'] != null)
            style="font-size: {{$prontuario->prontuario['impressao']['tamanho_fonte']}}px; padding: {{$prontuario->prontuario['impressao']['margem_cabecalho']}}cm {{$prontuario->prontuario['impressao']['margem_direita']}}cm {{$prontuario->prontuario['impressao']['margem_rodape']}}cm {{$prontuario->prontuario['impressao']['margem_esquerda']}}cm"
            @endif
            @endif>
            <div class="content-wrap">
                <div>
                    @if (array_key_exists('impressao', $prontuario->prontuario))
                        @if ($prontuario->prontuario['impressao'] != null)
                            {!! $prontuario->prontuario['impressao']['cabecalho'] !!}
                        @endif
                    @endif
                </div>
                <div >
                    {{-- <hr style="margin-top: 60px;"> --}}
                    @if ($exibir_titulo_paciente)
                        <p style="text-align: center"><b><span>PRONTUÁRIO</span></b></p>
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
                        @if ($prontuario->prontuario['tipo'] == 'old')
                            <div style="margin-top: 30px;">
                                @if ($obs)
                                    {!!$obs!!}
                                @else
                                    {!!$prontuario->prontuario['obs']!!}
                                @endif
                            </div>
                        @else
                            @if (array_key_exists('queixa_principal', $prontuario->prontuario))
                                @if ($prontuario->prontuario['queixa_principal'] != "")    
                                    <p><b>Queixa principal:</b></p>
                                    <p>{{$prontuario->prontuario['queixa_principal']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('h_m_a', $prontuario->prontuario))
                                @if ($prontuario->prontuario['h_m_a'] != "")    
                                    <p><b>H.M.A:</b></p>
                                    <p>{{$prontuario->prontuario['h_m_a']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('h_p', $prontuario->prontuario))
                                @if ($prontuario->prontuario['h_p'] != "")    
                                    <p><b>H.P:</b></p>
                                    <p>{{$prontuario->prontuario['h_p']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('h_f', $prontuario->prontuario))
                                @if ($prontuario->prontuario['h_f'] != "")    
                                    <p><b>H.F:</b></p>
                                    <p>{{$prontuario->prontuario['h_f']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('hipotese_diagnostica', $prontuario->prontuario))
                                @if ($prontuario->prontuario['hipotese_diagnostica'] != "")    
                                    <p><b>Hipótese diagnôstica:</b></p>
                                    <p>{{$prontuario->prontuario['hipotese_diagnostica']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('conduta', $prontuario->prontuario))
                                @if ($prontuario->prontuario['conduta'] != "")    
                                    <p><b>Conduta:</b></p>
                                    <p>{{$prontuario->prontuario['conduta']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('exame_fisico', $prontuario->prontuario))
                                @if ($prontuario->prontuario['exame_fisico'] != "")    
                                    <p><b>Exame fisico:</b></p>
                                    <p>{{$prontuario->prontuario['exame_fisico']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('obs', $prontuario->prontuario))
                                @if ($prontuario->prontuario['obs'] != "")    
                                    <p><b>Observações:</b></p>
                                    <p>{{$prontuario->prontuario['obs']}}</p>
                                    <hr>
                                @endif
                            @endif
                            @if (array_key_exists('cid', $prontuario->prontuario))
                                @if ($prontuario->prontuario['cid'] != "")    
                                    @if ($prontuario->prontuario['cid'] != "")
                                        <p><b>CID:</b></p>
                                        <p>{{$prontuario->prontuario['cid']['texto']}}</p>
                                        <hr>
                                    @endif
                                @endif
                            @endif
                        @endif
                        
                    </p>
                </div>   
            </div>   
            
            <div id="footer">
                @if (array_key_exists('impressao', $prontuario->prontuario))
                    @if ($prontuario->prontuario['impressao'] != null)
                        {!! $prontuario->prontuario['impressao']['rodape'] !!}
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
