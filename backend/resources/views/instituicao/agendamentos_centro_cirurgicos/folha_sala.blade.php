
<style>
    .negritoT{
        font-weight: bold!important;
    }
    .colorT{
        color: black!important;
    }
    body{
        color: black!important;
    }
    .textAR{
        text-align: right;
    }
    .textAC{
        text-align: center;
    }
    .textAL{
        text-align: left;
    }
    .paddingOutL{
        padding-left: 0px!important;
    }
</style>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('material/assets/images/logo-h.png') }}">
    <title>Instituição - Folha de Sala</title>
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
    <link href="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/iCheck/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/iCheck/skins/flat/blue.css') }}" rel="stylesheet">
    <link href="{{ asset('material/css/style.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('material/css/colors/blue.css') }}" id="theme" rel="stylesheet">

    <link href="{{ asset('material/assets/plugins/icheck/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/icheck/skins/flat/blue.css') }}" rel="stylesheet">

    <link href="{{ asset('material/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">



    <!-- chartist CSS -->
    <link href="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/chartist-js/dist/chartist-init.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <link href="{{ asset('material/assets/plugins/css-chart/css-chart.css') }}" rel="stylesheet">
    <!-- Vector CSS -->
    <link href="{{ asset('material/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />


    <link rel="stylesheet" href="{{ asset('material/assets/plugins/dropify/dist/css/dropify.min.css')}}">
</head>

<body>
    <div class="container">
        <p> <img src="{{ \Storage::cloud()->url($instituicao->imagem) }}" width="150" height="150" align="left">
           <div class="row" style="padding-top: 90px;">
                <div class="col-md-9 negritoT" style="">{{$instituicao->nome}}</div>
                <div class="col-md-3" style="text-align: left">Data: {{date('d/m/Y')}}</div>
                <div class="col-md-9" style="">Gastos da Cirurgia</div>
                <div class="col-md-3" style="text-align: left">Hora: {{date('H:i')}}</div>
           </div>
        </p>
        <hr style="border: top; border-color: black">
        <p>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="negritoT colorT textAC">Gastos da Cirurgia</h3>
                </div>
                <div class="col-md-4 textAL">Aviso de Cirurgia: {{$agendamento->id}}</div>
                <div class="col-md-8 textAL">Sala: {{$agendamento->salaCirurgica->sigla}}  {{$agendamento->salaCirurgica->descricao}} </div>
                <div class="col-md-3 textAL">Paciente: {{$agendamento->paciente_id}}</div>
                <div class="col-md-6 textAL">{{$agendamento->pessoa->nome}}  </div>
                {{-- <div class="col-md-3 textAL">Atendimento: {{$agendamento->id}}  </div> --}}
                <div class="col-md-12 textAL">Convênio Atend.: @if($agendamento->cirurgia) @if($agendamento->cirurgia->convenio_id) {{$agendamento->cirurgia->convenio_id}} {{$agendamento->cirurgia->convenioTrash->descricao}} @endif @endif</div>
                <div class="col-md-4 textAL">Data: {{date('d/m/Y', strtotime($agendamento->data))}}</div>
                <div class="col-md-4 textAL">Hora de inicio: {{date('H:i', strtotime($agendamento->hora_inicio))}}</div>
                <div class="col-md-4 textAL">Hora de termino: {{date('H:i', strtotime($agendamento->hora_final))}}</div>
            </div>
        </p>
        <p>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="negritoT colorT textAC">Cirurgia</h3>
                </div>
                <div class="col-md-12 textAL">Cirurgia: @if($agendamento->cirurgia) @if($agendamento->cirurgia->convenio_id) {{$agendamento->cirurgia->convenio_id}} {{$agendamento->cirurgia->convenioTrash->descricao}} @endif @endif</div>
                <div class="col-md-12 textAL">Anestesia: @if ($agendamento->cirurgia->tipo_anestesia_id)
                    {{$agendamento->cirurgia->tipoAnestesias->descricao}}
                @endif</div>
                @if (count($agendamento->outrasCirurgias)>0)
                    @foreach ($agendamento->outrasCirurgias as $item)
                        <div class="col-md-12 textAL">Cirurgia: @if($item->convenio_id) {{$item->convenio_id}} {{$item->convenioTrash->descricao}} @endif </div>
                        <div class="col-md-12 textAL">Anestesia: @if ($item->tipo_anestesia_id)
                            {{$item->tipoAnestesias->descricao}}
                        @endif</div>
                    @endforeach
                @endif
            </div>
        </p>
        <p>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="negritoT colorT textAC">Equipe Médica</h3>
                </div>
                <div class="col-md-12 textAL">Cirurgião: {{$agendamento->cirurgiao->id}} {{$agendamento->cirurgiao->nome}}</div>
                <div class="col-md-12 textAL">Anestesista: {{$agendamento->anestesista->id}} {{$agendamento->anestesista->nome}}</div>
                @if (count($agendamento->outrasCirurgiasCirurgiao)>0)
                    @foreach ($agendamento->outrasCirurgiasCirurgiao as $key => $item)
                    <div class="col-md-12 textAL">{{$key+1}} Auxiliar: {{$item->id}} {{$item->nome}}</div>
                    @endforeach
                @endif
            </div>
        </p>
        <p>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="negritoT colorT textAC">Materias e Equipamentos Utilizados na Cirurgia</h3>
                </div>
                @if (count($agendamento->equipamentos) > 0)
                    <div class="col-md-6 textAL">Equipamentos:</div>
                    <div class="col-md-6 textAL">Quantidade:</div>
                    <hr style="width: 100%;">
                    @foreach ($agendamento->equipamentos as $item)
                        <div class="col-md-6 textAL">{{$item->id}} {{$item->descricao}}</div>
                        <div class="col-md-6 textAL">{{$item->pivot->quantidade}}</div>
                    @endforeach
                    <hr style="width: 100%;">
                @endif
                @if (count($agendamento->caixasCirurgicas) > 0)
                    <div class="col-md-6 textAL">Caixar cirurgicas:</div>
                    <div class="col-md-6 textAL">Quantidade:</div>
                    <hr style="width: 100%;">
                    @foreach ($agendamento->caixasCirurgicas as $item)
                        <div class="col-md-6 textAL">{{$item->id}} {{$item->descricao}}</div>
                        <div class="col-md-6 textAL">{{$item->pivot->quantidade}}</div>
                    @endforeach
                    <hr style="width: 100%;">
                @endif
                @if (count($agendamento->sangueDerivados) > 0)
                    <div class="col-md-6 textAL">Sangues e derivados:</div>
                    <div class="col-md-6 textAL">Quantidade:</div>
                    <hr style="width: 100%;">
                    @foreach ($agendamento->sangueDerivados as $item)
                        <div class="col-md-6 textAL">{{$item->id}} {{$item->descricao}}</div>
                        <div class="col-md-6 textAL">{{$item->pivot->quantidade}}</div>
                    @endforeach
                    <hr style="width: 100%;">
                @endif
                @if (count($agendamento->produtos) > 0)
                    <div class="col-md-6 textAL">Produtos:</div>
                    <div class="col-md-3 textAL">Unidade:</div>
                    <div class="col-md-3 textAL">Quantidade:</div>
                    <hr style="width: 100%;">
                    @foreach ($agendamento->produtos as $item)
                        <div class="col-md-6 textAL">{{$item->id}} {{$item->descricao}}</div>
                        <div class="col-md-3 textAL">{{$item->unidade->descricao}}</div>
                        <div class="col-md-3 textAL">{{$item->pivot->quantidade}}</div>
                    @endforeach
                    <hr style="width: 100%;">
                @endif
            </div>
        </p>
        
    </div>

    <script type="text/javascript">
        window.print();
    </script>
</body>