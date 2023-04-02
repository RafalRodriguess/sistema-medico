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
        <div class="print-table" style="font-size: 12px; padding: 3cm 2.5cm 3cm 2.5cm">
            <div class="content-wrap">
                <div >
                    <h4 style="text-align: center"><b>Avaliação</b></h4>
                    <p>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="margin-bottom: 0px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left; border-top: 0px; padding: 0px"><h5><b>Paciente: </b>{{$agendamento->pessoa->nome}}<h5></td>
                                            <td style="text-align: right; border-top: 0px; padding: 0px"><h5><b>Data: </b>{{date("d/m/Y", strtotime($agendamento->data))}}<h5></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </p>

                    <hr>

                    <p>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="margin-bottom: 0px;">
                                    <tbody>
                                        <tr>
                                            <td style="border-top: 0px; padding: 0px">
                                                <h5><b>Médico: </b>@if($avaliacao->medico_id){{$avaliacao->prestador->nome}} @endif<h5>
                                            </td>
                                            <td style="border-top: 0px; padding: 0px">
                                                <h5><b>Especialidade: </b>@if($avaliacao->especialidade_id){{$avaliacao->especialidade->nome}} @endif<h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </p>

                    <p><div style="margin-top: 30px;">{!!$avaliacao->avaliacao!!}</div></p>
                </div>   
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
