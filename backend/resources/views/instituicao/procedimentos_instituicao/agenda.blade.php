@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Agendas",
        'breadcrumb' => [
            'Procedimentos' => route('instituicao.procedimentos.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <form @submit.prevent="submit" id='form' action="{{ route('instituicao.procedimentos.agenda.update', [$procedimento]) }}" method="post">
                @method('put')
                @csrf

        @php
            $semana=[

                    'domingo'=>(object)[
                        'nome' => 'Domingo',
                        'value' => 'domingo'
                    ],
                    'segunda'=>(object)[
                        'nome' => 'Segunda',
                        'value' => 'segunda'
                    ],
                    'terca'=>(object)[
                        'nome' => 'Terça',
                        'value' => 'terca'
                    ],
                    'quarta'=>(object)[
                        'nome' => 'Quarta',
                        'value' => 'quarta'
                    ],
                    'quinta'=>(object)[
                        'nome' => 'Quinta',
                        'value' => 'quinta'
                    ],
                    'sexta'=>(object)[
                        'nome' => 'Sexta',
                        'value' => 'sexta'
                    ],
                    'sabado'=>(object)[
                        'nome' => 'Sábado',
                        'value' => 'sabado'
                    ],

            ];
        @endphp
    <div class="card card-outline-info" >
        <div class="card-header">
            <h4 class="m-b-0 text-white">{{$procedimento->descricao}}</h4>
        </div>
        <div class="card-body">
            @foreach ($semana as $s)
                @php
                    $agenda = $InstituicaoProcedimentos->agenda()->where('dias_continuos',$s->value)->first();
                @endphp
                <div class='row @if($agenda || old('checkbox.'.$s->value)) panel @else no-panel @endif'>

                    <div class="form-group col-sm-1 col-xs-12" style="text-align:center">
                        <label for="checkbox[{{$s->value}}]" >{{$s->nome}}</label>
                        <div >
                                <input type="checkbox" class='form-control checkbox' @if($agenda || old('checkbox.'.$s->value)) checked @endif value="{{$InstituicaoProcedimentos->id}}" id='checkbox[{{$s->value}}]' name="checkbox[{{$s->value}}]" >
                        </div>
                    </div>
                    <div class="form-group col-sm-2 hidable  @if($errors->has('inicio.'.$s->value)) has-danger @endif">
                        <label for="inicio[{{$s->value}}]" >Horário de início</label>
                        <div class="input-group clockpicker" data-autoclose="true">
                            <input @if(!$agenda && !old('checkbox.'.$s->value)) disabled  @else readonly @endif  type="text" class="form-control" value="@if(old('inicio.'.$s->value)) {{old('inicio.'.$s->value)}} @elseif($agenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$agenda->hora_inicio)->format('H:i')}} @else 13:00 @endif" id="inicio[{{$s->value}}]" name="inicio[{{$s->value}}]">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('inicio.'.$s->value))
                            <div class="form-control-feedback">{{ $errors->first('inicio.'.$s->value) }}</div>
                        @endif
                        {{-- <input class="form-control clockpicker" type="time" value="13:00:00" id="-{{$s->value}}-inicio"> --}}
                    </div>
                    <div class="form-group @if($errors->has('intervalo.'.$s->value)) has-danger @endif col-sm-2 hidable ">
                        <label for="intervalo[{{$s->value}}]" >Horário do intervalo</label>
                        <div class="input-group clockpicker" data-autoclose="true">
                            <input @if(!$agenda && !old('checkbox.'.$s->value)) disabled @else readonly @endif  type="text" class="form-control" value="@if(old('intervalo.'.$s->value)) {{old('intervalo.'.$s->value)}} @elseif($agenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$agenda->hora_intervalo)->format('H:i')}} @else 15:45 @endif" id="intervalo[{{$s->value}}]" name="intervalo[{{$s->value}}]">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('intervalo.'.$s->value))
                            <div class="form-control-feedback">{{ $errors->first('intervalo.'.$s->value) }}</div>
                        @endif
                        {{-- <input class="form-control clockpicker" type="time" value="15:45:00" id="-{{$s->value}}-intervalo"> --}}
                    </div>
                    <div class="form-group col-sm-2 hidable @if($errors->has('duracao.'.$s->value)) has-danger @endif ">
                        <label for="duracao[{{$s->value}}]" >duracao do intervalo</label>
                        <div class="input-group clockpicker" data-autoclose="true">
                            <input @if(!$agenda && !old('checkbox.'.$s->value)) disabled @else readonly @endif type="text" class="form-control" value="@if(old('duracao.'.$s->value)) {{old('duracao.'.$s->value)}} @elseif($agenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$agenda->duracao_intervalo)->format('H:i')}} @else 00:15 @endif" id="duracao[{{$s->value}}]" name="duracao[{{$s->value}}]">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('duracao.'.$s->value))
                            <div class="form-control-feedback">{{ $errors->first('duracao.'.$s->value) }}</div>
                        @endif
                        {{-- <input class="form-control clockpicker" type="time" value="00:15:00" id="-{{$s->value}}-duracao-intervalo"> --}}
                    </div>
                    <div class="form-group col-sm-2 hidable @if($errors->has('termino.'.$s->value)) has-danger @endif ">
                        <label for="termino[{{$s->value}}]" >Horário de termino</label>
                        <div class="input-group clockpicker" data-autoclose="true">
                            <input @if(!$agenda && !old('checkbox.'.$s->value)) disabled @else readonly @endif type="text" class="form-control" value="@if(old('termino.'.$s->value)) {{old('termino.'.$s->value)}} @elseif($agenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$agenda->hora_fim)->format('H:i')}} @else 18:00 @endif" id="termino[{{$s->value}}]" name="termino[{{$s->value}}]">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('termino.'.$s->value))
                            <div class="form-control-feedback">{{ $errors->first('termino.'.$s->value) }}</div>
                        @endif
                        {{-- <input class="form-control clockpicker" type="time" value="18:00:00" id="-{{$s->value}}-termino"> --}}
                    </div>
                    <div class="form-group col-sm-3 hidable @if($errors->has('atendimento.'.$s->value)) has-danger @endif ">
                        <label for="atendimento[{{$s->value}}]" >Duração do Atendimento</label>
                        <div class="input-group clockpicker" data-autoclose="true">
                            <input @if(!$agenda && !old('checkbox.'.$s->value)) disabled @else readonly @endif  type="text" class="form-control" value="@if(old('atendimento.'.$s->value)) {{old('atendimento.'.$s->value)}} @elseif($agenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$agenda->duracao_atendimento)->format('H:i')}} @else 00:45 @endif" name="atendimento[{{$s->value}}]" id="atendimento[{{$s->value}}]">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('atendimento.'.$s->value))
                            <div class="form-control-feedback">{{ $errors->first('atendimento.'.$s->value) }}</div>
                        @endif
                        {{-- <input class="form-control clockpicker" type="time" value="00:45:00" id="-{{$s->value}}-atendimento"> --}}
                    </div>

                </div>
            @endforeach



            <div class="unicos">
                <div class="form-group" >
                Adicionar data especifica:
                    <span alt="default" class="add fas fa-plus-circle">
                        <a class="mytooltip" href="javascript:void(0)">
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar data especifica. Obs: Datas especificas irão sobrepor a agenda semanal"></i>
                        </a>
                    </span>
                </div>


                <div class="panel">
                    <div class="row ">
                        <div class=" col-md-3">
                            <div class="datepicker_vue"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">

                                <input type="text" class="form-control"  v-model='search' placeholder="Pesquisar data">
                            </div>
                            <div  class="scrollabe">
                                <div style="text-align: center;" v-for="date in filtered" :key='date.date' >
                                    <button @click="selectDate(date)" style='min-width: 140px;margin:5px;' type="button" :class="date.selected?'btn-success':'btn-primary'" class="btn waves-effect waves-light m-r-10"><i class="mdi" :class="date.selected?'mdi-minus':'mdi-plus'"></i> @{{date.date}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-7" v-if="filtered.find(o => o.selected==true)">
                            <div class="form-group col-sm-4">
                                <label >Horário de início</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" readonly class="form-control" v-model ="filtered.find(o => o.selected==true ).hora_inicio">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Horário do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" readonly class="form-control" v-model ="filtered.find(o => o.selected==true ).hora_intervalo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Duração do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" readonly class="form-control" v-model ="filtered.find(o => o.selected==true ).duracao_intervalo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Horário de termino</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" readonly class="form-control" v-model ="filtered.find(o => o.selected==true ).hora_fim">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8">
                                <label >Duração do Atendimento</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" readonly class="form-control" v-model ="filtered.find(o => o.selected==true ).duracao_atendimento">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-body">

                <div class="form-group text-right">
                        <a href="{{ route('instituicao.procedimentos.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>

        </div>
    </div>

    </form>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.4/vue.js"></script>
<script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('material/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

    <script>

        var vue = new Vue({
            el: '#form',
            data: {
                    @php
                        $agenda = $InstituicaoProcedimentos->agenda()->whereNotNull('dias_unicos')->first();
                    @endphp
                    agenda : {!!$agenda?($agenda->dias_unicos): '[]'!!},
                search: ''
            },

            mounted(){


                var self = this;
                $(".datepicker_vue").datepicker({
                    closeText: 'Fechar',
                    prevText: '<Anterior',
                    nextText: 'Próximo>',
                    currentText: 'Hoje',
                    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                    'Jul','Ago','Set','Out','Nov','Dez'],
                    dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 0,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: '',
                    minDate: 0,
                    onSelect: function (dateText, inst) {
                        if(!(moment(dateText,"DD-MM-YYYY").isBefore(moment(), "day")))
                        {
                            self.addOrRemoveDate(dateText);
                        }
                    },
                    beforeShowDay: function (date) {
                        var year = date.getFullYear();
                        var month = self.padNumber(date.getMonth() + 1);
                        var day = self.padNumber(date.getDate());
                        var dateString = day + "/" + month + "/" + year;
                        var gotDate = self.findDate(dateString);
                        if (gotDate >= 0) {
                            if(self.agenda[gotDate].selected==true){
                                return [true, "ui-state-highlight selected"];
                            }
                            return [true, "ui-state-highlight"];
                        }
                        return [true, ""];
                    }
                });
            },
            computed:{
                    filtered(){

                        var self = this;
                        return this.agenda.filter(function(item){
                            return item.date.substring(0, 5).includes(self.search)
                        }).filter(function(item){
                            return !(moment(item.date,"DD-MM-YYYY").isBefore(moment(), "day"))
                        })
                        .sort(function (a, b) {
                            if (moment(a.date,"DD-MM-YYYY").isAfter(moment(b.date,"DD-MM-YYYY"), "day")) {
                                return 1;
                            }
                            return -1;
                        })
                    },

            },
            methods: {

                selectDate(date){
                    selecionado = this.agenda.find(o => { return o.selected == true });
                    if(selecionado){
                        selecionado.selected = false
                        $('.datepicker_vue').datepicker("refresh");
                    }
                    if(selecionado!=date){

                        date.selected=true
                        $('.datepicker_vue').datepicker("setDate", date.date);
                        setTimeout(() => {
                            $('.clockpicker').clockpicker()
                            .find('input').change(function(e){
                                this.dispatchEvent(new Event('input', { target: e.target }));
                            })
                        }, 0);
                    }
                },

                addOrRemoveDate(date) {

                    var gotDate = this.findDate(date);
                    if (gotDate >= 0)
                        this.removeDate(gotDate);
                    else
                        this.addDate(date);
                },
                findDate(date){
                    return this.agenda.findIndex( o => {return o.date == date})
                },
                addDate(date) {

                    if(this.findDate(date) < 0){
                        date_object = {
                            'date': date,
                            'selected': false,
                            'hora_inicio' : '13:00',
                            'hora_fim' : '18:00',
                            'hora_intervalo' : '15:45',
                            'duracao_intervalo' : '00:15',
                            'duracao_atendimento' : '00:45',

                            }
                        this.agenda.push(date_object)
                        this.$forceUpdate()
                        this.selectDate(date_object)
                    }
                },

                removeDate(index ) {
                    this.agenda.splice(index, 1);
                    this.$forceUpdate()
                },
                padNumber(number) {
                    var ret = new String(number);
                    if (ret.length == 1)
                        ret = "0" + ret;
                    return ret;
                },
                submit(){
                    var form = document.getElementById('form');
                    var formData = new FormData(form);

                        if(this.agenda.find(o => { return o.selected == true })){
                            this.agenda.find(o => { return o.selected == true }).selected = false;
                        }
                        $('.datepicker_vue').datepicker("refresh");
                        formData.append("unicos",JSON.stringify(this.agenda))


                    $.ajax("{{ route('instituicao.procedimentos.agenda.update', [$InstituicaoProcedimentos]) }}", {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $.toast({
                                heading: response.title,
                                text: response.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: response.icon,
                                hideAfter: 3000,
                                stack: 10
                            });


                        },
                        error: function (response) {

                            if(response.responseJSON.errors){
                                Object.keys(response.responseJSON.errors).forEach(function(key) {
                                    $.toast({
                                        heading: 'Erro',
                                        text: response.responseJSON.errors[key][0],
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
                    // var formData = new FormData($('#form')[0]);

                }

            }
        })

        $( document ).ready(function() {

            $('.clockpicker').clockpicker().find('input').change(function(){
                $(this).attr('value', this.value);
            });

            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            }).on('ifChecked', function (event) {
                $(this).closest('.row').removeClass('no-panel').addClass('panel')
                // $(this).closest('.row').find('.hidable').removeClass('hidden')
                $(this).closest('.row').find('.hidable').find('input[disabled="disabled"]').each(function () {
                    $(this).attr("disabled", false);
                    $(this).attr("readonly", true);
                })
                $(this).closest('.row').find('.hidable').removeClass('hidden')
                event.currentTarget.setAttribute("checked", "checked");
            }).on('ifUnchecked', function (event) {
                $(this).closest('.panel').removeClass('panel').addClass('no-panel')
                $(this).closest('.row').find('.hidable').find('input[readonly="readonly"]').each(function () {
                    $(this).attr("disabled", "disabled");
                    $(this).attr("readonly", false);
                })
                // $(this).closest('.row').find('.hidable').addClass('hidden')
                event.currentTarget.removeAttribute("checked");
            }).on('ifChanged', function (event) {
            });




        })
    </script>

@endpush

@push('estilos')
<style>
        .form-control[readonly] {
            opacity: 1;
        }

        .form-control[readonly] {
            background-color: #fff;
        }

        .hidden{
            display:none;
        }

        .no-panel{
            background-color: #dbdbdc;
            padding: 10px 10px 0px 10px;
            margin-bottom: 10px;
                border: black 1px solid;
        }

		.panel{
			background-color: #d3e1fb;
            padding: 10px 10px 0px 10px;
            margin-bottom: 10px;
                border: black 1px solid;
		}

        td.ui-state-highlight.selected > a{
            background: inherit;
            border: inherit;
            color: #ffffff;
        }
        .ui-state-active, .ui-widget-content .ui-state-active{
            background: #1eacbe;
        }
        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #1eacbe;
            background: #1eacbe;
        }

        .ui-datepicker-today > .ui-state-highlight{
            border: 1px solid #c5c5c5;
            background: #f6f6f6;
        }

        .ui-state-active,
        .ui-widget-content .ui-state-active,
        .ui-widget-header .ui-state-active,
        a.ui-button:active,
        .ui-button:active,
        .ui-button.ui-state-active:hover {
            border: 1px solid #cccccc;
            background: #f6f6f6;
            color: #454545;
        }
        </style>
        <style>


    .scrollabe {
        overflow-y: scroll;
        max-height: 235px;
        margin-bottom: 10px;
    }


    .scrollabe::-webkit-scrollbar {
        width: 7px;
    }

    .scrollabe::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scrollabe::-webkit-scrollbar-thumb {
        background: #1E88E5;
        border-radius: 10px;
    }

    .scrollabe::-webkit-scrollbar-thumb:hover {
        background: #0F4473;
    }
</style>
@endpush

