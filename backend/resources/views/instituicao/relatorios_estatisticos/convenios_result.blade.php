<div class="card">
    <div class="card-body ">
        <input type="hidden" id="porProcedimento" value="{{json_encode($porProcedimento)}}">
        <h3 class="card-title">Procedimentos</h3>
        <div id="procedimentos"></div>
    </div>
</div>

<div class="card">
    <div class="card-body ">
        <input type="hidden" id="porPrestador" value="{{json_encode($porPrestador)}}">
        <h3 class="card-title">Prestadores</h3>
        <div id="prestadores"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        chartProcedimentos();
        chartPrestador();
        // chartProfissional();
    })

    function chartProcedimentos(){
        var dados = JSON.parse($("#porProcedimento").val());
     
        var chart = c3.generate({
            bindto: '#procedimentos',
            data: {
                x: 'x',
                columns: dados,
                type: 'spline',
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
            tooltip: {
                grouped: false // Default true
            }
            // color: {
            //     pattern: ['#009688', '#1e88e5']
            // }
        });
    }

    function chartPrestador(){
        var dados = JSON.parse($("#porPrestador").val());

        console.log(dados);
     
        var chart = c3.generate({
            bindto: '#prestadores',
            data: {
                x: 'x',
                columns: dados,
                type: 'spline',
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
            tooltip: {
                grouped: false // Default true
            }
            // color: {
            //     pattern: ['#009688', '#1e88e5']
            // }
        });
    }

    // function chartProfissional(){
    //     var dados = JSON.parse($("#profissional_valores").val());

    //     var chart = c3.generate({
    //         bindto: '#porProfissional',
    //         data: {
    //             x: 'x',
    //             columns: dados,
                
    //             // type : 'timeseries',
    //             onclick: function (d, i) { console.log("onclick", d, i); },
    //             onmouseover: function (d, i) { console.log("onmouseover", d, i); },
    //             onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    //         },
    //         axis : {
    //             x : {
    //                 type : 'timeseries',
    //                 tick: {
    //                     // format: function (x) { return x.getFullYear(); }
    //                     format: '%Y-%m' // format string is also available for timeseries data
    //                 }
    //             },
    //             y: {
    //                 tick: {
    //                     format: d3.format(",")
    //                     // format: function (d) { return d.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); }
    //                 }
    //             }
    //         },            
    //         legend: {
    //             show: false
    //         },
    //         tooltip: {
    //             grouped: false // Default true
    //         }
    //         // color: {
    //         //     pattern: ['#009688', '#fc4b6c', '#1e88e5']
    //         // }
    //     });
    // }
</script>