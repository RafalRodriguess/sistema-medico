<div class="card">
    <div class="card-body ">
        <input type="hidden" id="faturamento_valores" value="{{$faturamento}}">
        <h3 class="card-title">Faturamento</h3>
        <div id="faturamento"></div>
    </div>
</div>

<div class="card">
    <div class="card-body ">
        <input type="hidden" id="despesas_valores" value="{{$despesas}}">
        <h3 class="card-title">Despesas</h3>
        <div id="despesas"></div>
    </div>
</div>

<div class="card">
    <div class="card-body ">
        <h3 class="card-title">Faturamento x Despesasas</h3>
        <div id="faturamentoXdespesas"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        chartFaturamento();
        chartDespesas();
        chartFaturamentoXDespesas();
    })

    function chartFaturamento(){
        var dados = JSON.parse($("#faturamento_valores").val());
        var label = ['x'];
        var data = ['Faturamento']        

        for(i = 0; i < dados.length; i++ ){
            label.push(dados[i].mes);
            data.push(parseFloat(dados[i].valor));
        }

        var chart = c3.generate({
            bindto: '#faturamento',
            data: {
                x: 'x',
                columns: [
                    label,
                    data,
                ],
                
                type : 'spline',
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
                        format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
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

    function chartDespesas(){
        var dados = JSON.parse($("#despesas_valores").val());
        var label = ['x'];
        var data = ['Despesas']        

        for(i = 0; i < dados.length; i++ ){
            label.push(dados[i].mes);
            data.push(parseFloat(dados[i].valor));
        }

        var chart = c3.generate({
            bindto: '#despesas',
            data: {
                x: 'x',
                columns: [
                    label,
                    data,
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
                        format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
                    }
                }
            },            
            legend: {
                hide: true
            },
            color: {
                pattern: ['#fc4b6c', '#1e88e5']
            }
        });
    }

    function chartFaturamentoXDespesas(){
        var despesas = JSON.parse($("#despesas_valores").val());
        var faturamento = JSON.parse($("#faturamento_valores").val());
        var label = ['x'];
        var data1 = ['Faturamento'];
        var data2 = ['Despesas'];     

        for(i = 0; i < despesas.length; i++ ){
            label.push(despesas[i].mes);
            data1.push(parseFloat(faturamento[i].valor));
            data2.push(parseFloat(despesas[i].valor));
        }

        var chart = c3.generate({
            bindto: '#faturamentoXdespesas',
            data: {
                x: 'x',
                columns: [
                    label,
                    data1,
                    data2
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
                        // format: d3.format("$,")
                        format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
                    }
                }
            },            
            legend: {
                hide: true
            },
            color: {
                pattern: ['#009688', '#fc4b6c', '#1e88e5']
            }
        });
    }
</script>