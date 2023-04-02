<div class="card">
    <div class="card-body ">
        <input type="hidden" id="agendamentos_valores" value="{{$agendamento}}">
        <h3 class="card-title">Agendamentos</h3>
        <div id="agendamentos"></div>
    </div>
</div>

<div class="card">
    <div class="card-body ">
        <input type="hidden" id="status_valores" value="{{json_encode($porStatus, true)}}">
        <h3 class="card-title">Agendamentos por Status</h3>
        <div id="porStatus"></div>
    </div>
</div>

<div class="card">
    <div class="card-body ">
        <input type="hidden" id="convenios_valores" value="{{json_encode($porConvenio, true)}}">
        <h3 class="card-title">Agendamentos por Convenio</h3>
        <div id="porConvenios"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        chartAgendamentos();
        chartPorStatus();
        chartPorConvenios();
    })

    function chartAgendamentos(){
        var dados = JSON.parse($("#agendamentos_valores").val());
        var label = ['x'];
        var data = ['Agendamentos']        

        for(i = 0; i < dados.length; i++ ){
            label.push(dados[i].mes);
            data.push(dados[i].total);
        }

        var chart = c3.generate({
            bindto: '#agendamentos',
            data: {
                x: 'x',
                columns: [
                    label,
                    data,
                ],
                
                type: 'bar',
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }
            },
            axis : {
                x : {
                    type : 'timeseries',
                    tick: {
                        // format: function (x) { return x.getFullYear(); }
                        format: '%Y-%m' // format string is also available for timeseries data
                    }
                },
                y: {
                    tick: {
                        format: d3.format(",")
                        // format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
                    }
                }
            },            
            legend: {
                hide: true
            },
            color: {
                pattern: ['#009688', '#1e88e5']
            }
        });
    }

    function chartPorStatus(){
        var dados = JSON.parse($("#status_valores").val());
        var label = ['x'];
        var agendado = ['Agendados'];
        var atendido = ['Atendidos']   
        var confirmado = ['Confirmados']   
        var ausente = ['Ausentes']   
        var cancelado = ['Cancelados'] 
       
        for(key in dados){
            label.push(key);
            
            agendado.push(dados[key].agendados);
            atendido.push(dados[key].atendidos);
            confirmado.push(dados[key].confirmados);
            ausente.push(dados[key].ausentes);
            cancelado.push(dados[key].cancelados);
        }

        var chart = c3.generate({
            bindto: '#porStatus',
            data: {
                x: 'x',
                columns: [
                    label,
                    agendado,
                    atendido,
                    confirmado,
                    ausente,
                    cancelado,
                ],
                
                // type : 'timeseries',
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }
            },
            axis : {
                x : {
                    type : 'timeseries',
                    tick: {
                        // format: function (x) { return x.getFullYear(); }
                        format: '%Y-%m' // format string is also available for timeseries data
                    }
                },
                y: {
                    tick: {
                        format: d3.format(","),
                        // format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
                    }
                }
            },            
            legend: {
                hide: true
            },
            color: {
                pattern: ['#26c6da', '#745af2', '#009688', '#899093', '#fc4b6c']
            }
        });
    }

    function chartPorConvenios(){
        var dados = JSON.parse($("#convenios_valores").val());

        var chart = c3.generate({
            bindto: '#porConvenios',
            data: {
                x: 'x',
                columns: dados,
                
                // type : 'timeseries',
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }
            },
            axis : {
                x : {
                    type : 'timeseries',
                    tick: {
                        // format: function (x) { return x.getFullYear(); }
                        format: '%Y-%m' // format string is also available for timeseries data
                    }
                },
                y: {
                    tick: {
                        format: d3.format(",")
                        // format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
                    }
                }
            },            
            legend: {
                show: false
            },
            tooltip: {
                grouped: false // Default true
            }
            // color: {
            //     pattern: ['#009688', '#fc4b6c', '#1e88e5']
            // }
        });
    }
</script>