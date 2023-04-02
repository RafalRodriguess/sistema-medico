
{{-- <script src="{{ asset('material/assets/plugins/calendar/jquery-ui.min.js') }}"></script> --}}
<script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('material/assets/plugins/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('material/assets/plugins/calendar/dist/cal-init.js') }}"></script>
<script src='{{ asset('material/assets/plugins/calendar/dist/locale-all.js') }}'></script>

<input type="hidden" name="data_inicio_semana" id="data_inicio_semana" value="{{$data['inicio']}}">
<input type="hidden" name="data_fim_semana" id="data_fim_semana" value="{{$data['fim']}}">
<div id="calendar-semanal"></div>

<style>
    .fc-event{
        cursor: pointer!important;
    }
</style>
<script>

    var datasIniciadas = [];
    var data_inicial = $("#data_inicio_semana").val();
    var data_final = $("#data_fim_semana").val();
    var calendar = $('#calendar-semanal')

    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    $(document).ready(function() {
        
        datasIniciadas.push($(".tab-content").find("#data").val())
        

        // calendar.fullCalendar({
            
        //     events: function(start, end, timezone, callback) {
                $.ajax({
                    url: "{{ route('instituicao.agendamentos.getDadosSemanal') }}",
                    dataType: 'json',
                    data: {
                        'prestador': $(".tab-content").find("#prestador option:selected").val(),
                        'data': $(".tab-content").find("#data").val(),
                    },
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(response) {
                        const date = moment(response.data_inicio); // Thursday Feb 2015
                        const dow = date.day()
                        //get your events from response.events
                        calendar.fullCalendar({
                            defaultView: 'agendaWeek',
                            header: {
                                left: 'prev,next',
                                center: 'title,prestador',
                                right: 'agendaWeek,atualizar',
                            },
                            customButtons: {
                                prev: {
                                    text: '<',
                                    click: function() {
                                        calendar.fullCalendar('prev');
                                        getDadosSemanal();

                                    }
                                },
                                next: {
                                    text: '>',
                                    click: function() {
                                        calendar.fullCalendar('next');                                        
                                        getDadosSemanal();

                                    }
                                },
                                atualizar: {
                                    text: 'Atualizar',
                                    click: function() { 
                                        datasIniciadas = []; 
                                        calendar.fullCalendar('removeEvents');                                    
                                        calendar.fullCalendar('rerenderEvents');                                    
                                        getDadosSemanal();
                                    }
                                },
                                prestador: {
                                    text:  $(".tab-content").find("#prestador option:selected").text(),
                                    click: function(){
                                        changePrestadorSemanal()
                                    }
                                }
                            },
                            eventRender: function(eventObj, $el) {
                                $el.popover({
                                    title: eventObj.horarioAgendamento,
                                    content: eventObj.texto,
                                    trigger: 'hover',
                                    placement: 'top',
                                    container: 'body'
                                });
                            },
                            slotLabelFormat: ['HH:mm'],
                            locale: 'pt-br',
                            slotEventOverlap: false,
                            // buttonIcons: false, // show the prev/next text
                            weekNumbers: true,
                            // navLinks: true, // can click day/week names to navigate views
                            // editable: false,
                            // eventLimit: true, // allow "more" link when too many events
                            firstDay: dow,
                            defaultDate: response.data_inicio,
                            minTime: response.inicio_horario,
                            maxTime: response.fim_horario,
                            slotDuration: response.tempo_duracao,
                            slotLabelInterval: response.tempo_duracao,
                            events: response.horarios,
                            eventMinHeight: 40,
                            expandRows: false,
                            eventClick: function(calEvent, jsEvent, view) {

                                // alert('Status: ' + calEvent.status);
                                // alert('Id: ' + calEvent.id);
                                // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                                // alert('View: ' + view.name);
                                // console.log(calEvent);
                                var mes = manipulaMesMoment(calEvent.start.get('month'));
                                mes++;
                                var dia = manipulaMesMoment(calEvent.start.get('date'));
                                var ano = calEvent.start.get('year');

                                var hora = calEvent.horarioAgendamento.split('-');
                                var hora_inicio = hora[0];
                                // console.log(dia+"/"+mes+'/'+ano);
                                //CHAMA O MODAL REFERENTE AO STATUS
                                if(calEvent.status == "livre"){
                                    var diaInserir = dia+"/"+mes+'/'+ano;
                                    callInserirAgendamentoSemanal(diaInserir, hora[0]);
                                }else{
                                    if(calEvent.status != "ausente"){
                                        if(calEvent.texto != "Horário cancelado"){
                                            var agendamento_id = calEvent.id;
                                            callDescricaoAgendamentoSemanal(agendamento_id);
                                        }
                                    }
                                }

                            }
                        });    
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                    },
                });
            // }
        // });


    });

    function changePrestadorSemanal(){
        $("#modalChangePrestadorSemanal").modal('show')
    }

    function callRenderSemanal(dataSemanal){
        if(verificaDataInciada(dataSemanal)){
            $.ajax({
                url: "{{ route('instituicao.agendamentos.getDadosSemanalDia') }}",
                dataType: 'json',
                data: {
                    'prestador': $(".tab-content").find("#prestador option:selected").val(),
                    'data': dataSemanal,
                },
                beforeSend: () => {
                    setTimeout(() => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    }, 0);
                },
                success: function(response) {
                    //REMOVE HORARIOS E INSERE DE NOVO
                    calendar.fullCalendar("removeEvents", function(eventObject) {
                        var startData = eventObject.start.toISOString().replace('T', ' ');
                        if(response.removes.includes(startData)){
                            return true
                        }
                    });
                    calendar.fullCalendar('addEventSource', response.horarios);
                    calendar.fullCalendar('rerenderEvents');
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
            });
        }
    }

    function verificaDataInciada(dataSemanal){
        //VERIFICA SE A DATA JA FOI CARREGADA
        var dataInicio = '';
        var dataFim = '';
        dataSemanal = montaDataSemanal(dataSemanal);
        for (let index = 0; index < datasIniciadas.length; index++) {
            const element = datasIniciadas[index];
            if(datasIniciadas.length > 1){
                
                var dataElement = montaDataSemanal(element);
                dataElement.setDate(dataElement.getDate()+6);
                console.log(dataElement)

                if(dataSemanal <= dataElement){
                    if(dataFim == ""){
                        dataFim = element;
                    }else{
                        if(dataFim > montaDataSemanal(element)){
                            dataFim = dataElement;
                        }
                    }
                }
            }else{
                var dataElement = montaDataSemanal(element);
                dataElement.setDate(dataElement.getDate()+6);
                if(dataSemanal <= dataElement){
                    if(dataFim == ""){
                        dataFim = element;
                    }else{
                        if(dataFim > montaDataSemanal(element)){
                            dataFim = dataElement;
                        }
                    }
                }
            }

            if(dataSemanal >= montaDataSemanal(element)){
                if(dataInicio == ""){
                    dataInicio = element;
                }else{
                    if(dataInicio < montaDataSemanal(element)){
                        dataInicio = element;
                    }
                }
            }

        }

        if(dataInicio != '' && dataFim != ''){
            return true;
        }
        return false;
    }

    function montaDataSemanal(strData){
        var partesData = strData.split("/");
        var dataMonta = new Date(partesData[2], partesData[1] - 1, partesData[0]);
        return dataMonta;
    }

    function callDescricaoAgendamentoSemanal(agendamento_id){
        $('#modalDescricao .modal-content').html('');
        // console.log($(this).data('agendamento'))
        $.ajax("{{ route('instituicao.agendamentos.modalDescricao') }}", {
                method: "POST",
                data: {agendamento_id: agendamento_id, '_token': '{{csrf_token()}}'},
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    $('#modalInserirAgenda .modal-content').html('');
                    $('#modalDescricao .modal-content').html(response);
                    $('#modalDescricao').modal('show')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
        })
    }

    function manipulaMesMoment(dataManipula){
        if(dataManipula < 10){
            dataManipula = "0"+dataManipula;
        }

        return dataManipula;
    }

    function callInserirAgendamentoSemanal(dataInserir, horario){
        $('#modalInserirAgenda .modal-content').html('');
        var prestador = $(".tab-content").find("#prestador option:selected").val()
        $.ajax("{{ route('instituicao.agendamentos.modalInserirAgenda') }}", {
            method: "POST",
            data: {
                data: dataInserir,
                horario:  horario,
                prestador_especialidade_id: prestador,
                '_token': '{{csrf_token()}}'
            },
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (response) {
                $('#modalDescricao .modal-content').html('');
                if(response.icon == "error"){
                    $.toast({
                        heading: response.title,
                        text: response.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: response.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                }else{
                    
                    $('#modalInserirAgenda .modal-content').html(response);
                    $('#modalInserirAgenda').modal('show')
                }
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
            },
    })
    }

    function getDadosSemanal(dates){
        //PEGA O DIA INICIAL
        var dados = calendar.fullCalendar('getCalendar');
        var view = dados.view;
        var start = view.start._d;
        var end = view.end._d;
        var dates = { start: start.toISOString(), end: end.toISOString() };
        
        //TRANSFORMA PARA VER SE EXISTE E SE N EXISTIR ENVIAR
        start = dates.start.split('T');
        start = start[0].split('-');
        start = start[2]+'/'+start[1]+'/'+start[0];

        if(!datasIniciadas.includes(start)){
            datasIniciadas.push(start)
            $.ajax({
                url: "{{ route('instituicao.agendamentos.getDadosSemanal') }}",
                dataType: 'json',
                data: {
                    'prestador': $(".tab-content").find("#prestador option:selected").val(),
                    'data': start,
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(response) {
                    
                    calendar.fullCalendar( "addEventSource", response.horarios)
                    
                    if(comparaHorasSemanal(calendar.fullCalendar("option", "maxTime"), response.fim_horario)){
                        calendar.fullCalendar("option", "maxTime", response.fim_horario)
                    }
                    if(comparaHorasSemanal(calendar.fullCalendar("option", "minTime"), response.inicio_horario)){
                        calendar.fullCalendar("option", "minTime", response.inicio_horario)
                    }
                    if(comparaHorasSemanal(calendar.fullCalendar("option", "slotDuration"), response.tempo_duracao)){
                        calendar.fullCalendar("option", "slotDuration", response.tempo_duracao)
                    }
                    if(comparaHorasSemanal(calendar.fullCalendar("option", "slotLabelInterval"), response.tempo_duracao)){
                        calendar.fullCalendar("option", "slotLabelInterval", response.tempo_duracao)
                    }
                    // calendar.fullCalendar('renderEvent', response.horarios);
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
            });
        }
    }
    function comparaHorasSemanal(antiga, nova){
        hora1 = antiga.split(":");
        hora2 = nova.split(":");

        var d = new Date();
        var data1 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora1[0], hora1[1]);
        var data2 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hora2[0], hora2[1]);
        
        if(data1 > data2){
            return false;
        }

        return true
    }

</script>