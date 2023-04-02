{{-- <script src="{{ asset('material/js/morris/morris.js')}}"></script>
<script src="{{ asset('material/js/morris/raphael-2.1.0.min.js')}}"></script> --}}
<style>
    .selectProcedimento .select2-selection {
        /* overflow:auto; */
        height: 100px !important;
        
        white-space: normal;
        word-wrap: break-word;
        display: block;
    }

    .selectProcedimento .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 100px!important;
        overflow-y: auto;
    }

    .selectProcedimento .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar{
        width: 5px;
    }
    
    .selectProcedimento .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .selectProcedimento .select2-container--default .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar-thumb
    {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #c1c1c1;
    }

    .selectProcedimento .select2-selection__choice {
        font-size: 14px;
        margin: 2px !important;
        color: black;
    }

    .selectProcedimento li.select2-selection__choice {
        max-width: 100%;
        /* overflow: auto; */
        
        word-wrap: normal !important;
        white-space: normal;
    }

    .selectProcedimento ul.select2-selection__rendered {
        padding-right: 12px !important;
    }

    .no_show{display: none !important;}
    .orcamentos-table {
        border-left:1px solid #ddd!important;
        border-right:1px solid #ddd!important;
        width: 100%!important;
    }
    .orcamentos-table th, .orcamentos-table td {
        border: 1px !important;
    }
    .orcamentos-table td {
        border-bottom: 1px solid #ddd!important;
    }
    .fixed-table-container {
        /* border-left:0px !important; */
        /* border-right:0px !important; */
    }
    .fixed-table-body::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .fixed-table-body::-webkit-scrollbar
    {
        width: 7px;
        background-color: #F5F5F5;
    }

    .fixed-table-body::-webkit-scrollbar-thumb
    {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #c1c1c1;
    }
    .text-center{
        text-align: center;
    }
    .label{
        background-color: #d1dade;
        color: #5e5e5e;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        text-shadow: none;
    }
    .criado{
        color: #5e5e5e;
    }
    .aprovado{
        background-color: #1ab394;
        border-color: #1ab394;
        color: #ffffff;
    }
    .reprovado{
        background-color: #ed5565;
        border-color: #ed5565;
        color: #ffffff;
    }

    .procedimentos-scroll {
        height: 250px;
        overflow: auto;
        width: 100%;
        padding: 0px;      
        margin: 0px;
    }

    .procedimentos-scroll::-webkit-scrollbar{
        width: 5px;
    }
    
    .procedimentos-scroll::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .procedimentos-scroll::-webkit-scrollbar-thumb
    {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #c1c1c1;
    }

    .procedimentos-wrapper-scroll{
        display: block;
    }
    
    /* #canvas_dental svg{
        z-index: 9999;
    } */

</style>
<link href="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
<script src="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.ints.js')}}"></script>
<script src="{{ asset('material/js/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('material/js/dentes.js')}}"></script>

<div class="row">
    <div class="col-md-3 canvas_class">
        <div id="canvas_dental"></div>
    </div>
    <div class="col-md-5 procedimentos_class">
        <div class="row">
            <input type="hidden" name="orcamento_id_edit" id="orcamento_id_edit" value="">
            <div class="form-group col-md-6">
                <select name="grupo_odontologico" id="grupo_odontologico" class="form-control select2Convenio" onchange="getProcedimentos(this)" style="width: 100%">
                    <option value="">Selecione um grupo</option>
                    @foreach ($grupos as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6 selectProcedimento">
                <select name="procedimentos_odontologico" id="procedimentos_odontologico" class="form-control select2Procedimento" multiple style="width: 100%">
                </select>
            </div>
            <div class="col-md-12 regioes" style="display: none">
                <h5 class="card-title">Região</h5>
                <div class="demo-radio-button">
                    <div class="itens_regiao_normal">
                        @foreach ($regioes as $item)
                            @if (!$item->tipo_limpeza)    
                                <input name="regioes_procedimentos" type="checkbox" value="{{$item->id}}" data-name="{{$item->descricao}}" id="regiao_{{$item->id}}"  />
                                <label for="regiao_{{$item->id}}" class="itens_regiao" data-limpeza="{{$item->tipo_limpeza}}">{{$item->descricao}}</label>
                            @endif
                        @endforeach
                    </div>
                    <div class="itens_regiao_limpeza">
                        @foreach ($regioes as $item)
                            @if ($item->tipo_limpeza)    
                                <input name="regioes_procedimentos" type="checkbox" value="{{$item->id}}" data-name="{{$item->descricao}}" id="regiao_{{$item->id}}"  />
                                <label for="regiao_{{$item->id}}" class="itens_regiao" data-limpeza="{{$item->tipo_limpeza}}">{{$item->descricao}}</label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-groupn text-right pb-2">
                    <button type="button" class="btn btn-success waves-effect waves-light adicionar_procedimento_odontologico">Adicionar procedimento</button>
                </div>
            </div>
            <hr style="width: 100%;">
            <div class="col-md-12">
                <table class="table procedimentos procedimentos-scroll procedimentos-wrapper-scroll">
                    <thead>
                        <tr>
                            <th width="30%">Dentes</th>
                            <th width="30%">Procedimentos</th>
                            <th width="30%">Regiões</th>
                            <th class="text-center" width="10%">Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="procedimento-base" style="display: none;">
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-groupn col-md-12 text-right pb-2" style="margin-top: 15px;">
                @can('habilidade_instituicao_sessao', 'cadastrar_orcamento_odontologico')
                    <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-odontologico-button" style="float: right"><i
                    class="far fa-save"></i>
                    Salvar</button>
                @endcan
                <button type="button" class="btn btn-danger waves-effect waves-light m-r-10 cancelar-edicao-odontologico-button" style="float: right; display: none">
                Cancelar edição</button>
            </div>
        </div>
    </div>
    <div class="col-md-4 orcamentos_class">
        <div style="text-align: center; border: 1px solid; border-color: rgba(0,0,0,.125)">
            <h5 style="padding: 10px;">Orçamentos</h5>
        </div>
        <table data-toggle="table" class="orcamentos-table" data-height="295" data-mobile-responsive="true">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="orcamentos-table-body">
                
            </tbody>
            <tfoot class="orcamento-table-footer" style="display: none">
                <tr>
                    <td colspan="4">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div id="modal-orcamentos"></div>

<script>
    var dentesSelecionados = [];
    var procedimentos = [];
    var exigeRegiao = 0;
    var procedimentoOptions = [];
    var arrayId = [];
    var desconto_maximo = "{{$desconto_maximo}}";

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
        renderDentalChart('canvas_dental');
        $(".select2Convenio").select2();
        $(".select2Procedimento").select2({
            placeholder: 'Selecione os procedimentos'
        });
        carregaTabaleOrcamentos();
        if($(window).width() <= 1269){
            $(".canvas_class").removeClass('col-md-3')
            $(".procedimentos_class").removeClass('col-md-5')
            $(".orcamentos_class").removeClass('col-md-4')
            $(".canvas_class").addClass('col-md-4')
            $(".procedimentos_class").addClass('col-md-5')
            $(".orcamentos_class").addClass('col-md-3')
        }
    })

    function geraTabelaOrcamento(result){
        $('.orcamentos-table-body').html(result);
        $('[data-toggle="tooltip"]').tooltip()
    }

    function carregaTabaleOrcamentos(){
        var paciente_id = $("#paciente_id").val();

        $.ajax({
            url: "{{route('instituicao.odontologico.getTableOrcamento', ['paciente' => 'paciente_id'])}}".replace('paciente_id', paciente_id),
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            datatype: "json",
            beforeSend: () => {
                $('.orcamentos-table-body').html('');
                $(".orcamento-table-footer").css('display', 'block');
            },
            success: function(result) {
                geraTabelaOrcamento(result)
            },
            complete: () => {
                $(".orcamento-table-footer").css('display', 'none');
            }
        });
    }

    function displayRegiao(){
        if(exigeRegiao == 1){
            $(".regioes").css('display', 'block');
            var limpeza = $("#procedimentos_odontologico").find('option:selected').attr('data-limpeza');
            if(limpeza == 'true'){
                $('.itens_regiao_normal').css('display', 'none')
                $('.itens_regiao_limpeza').css('display', 'block')
            }else{
                $('.itens_regiao_normal').css('display', 'block')
                $('.itens_regiao_limpeza').css('display', 'none')
            }
        }else{
            $(".regioes").css('display', 'none');
        }
    }

    function getProcedimentos(element){
        var id = $(element).val()
        var options = $(".select2Procedimento");
        if(id){
            $.ajax({
                url: "{{route('instituicao.odontologico.getProcedimentos', ['grupo' => 'grupo_id'])}}".replace('grupo_id', id),
                type: "POST",
                data: {
                "_token": "{{ csrf_token() }}",
                },
                datatype: "json",
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                if(result != null){
                    procedimentoOptions = [];
                    procedimentosResult = result
                    procedimentoOptions = result;
                    $('.select2Procedimento').val('').change();
    
                    options.find('option').remove();
    
                    $.each(procedimentosResult, function (key, value) {
                        options.append('<option value='+value.procedimento.id+' data-regiao='+value.procedimento.possui_regiao+' data-limpeza='+value.procedimento.tipo_limpeza+'>'+value.procedimento.descricao+'</option>')
                    });
                }
                },
                complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
                }
            });
        }else{
            options.find('option').remove();
        }
    }

    $("#procedimentos_odontologico").on('change', function(e){
        var ids = $("#procedimentos_odontologico").val();
        if(ids.length > 0){
            var id = $("#procedimentos_odontologico").find('option:selected').val()
            var regiao = $("#procedimentos_odontologico").find('option:selected').attr('data-regiao');
            if(regiao == 'true' && exigeRegiao == 0){
                exigeRegiao = 1;
                var novosids = [];
                novosids.push(id)
                $("#procedimentos_odontologico").val(novosids).change()
                $("#procedimentos_odontologico").find('option').filter(':not([value="'+id+'"])').remove();
                $.each(procedimentoOptions, function (key, value) {
                    if(value.procedimento.id != id){
                        $("#procedimentos_odontologico").append('<option value='+value.procedimento.id+' data-regiao='+value.procedimento.possui_regiao+' data-limpeza='+value.procedimento.tipo_limpeza+' disabled>'+value.procedimento.descricao+'</option>')
                    }
                });
                
            }
        }else{
            exigeRegiao = 0;
            $("#procedimentos_odontologico").find('option').remove();
            $.each(procedimentoOptions, function (key, value) {
                $("#procedimentos_odontologico").append('<option value='+value.procedimento.id+' data-regiao='+value.procedimento.possui_regiao+' data-limpeza='+value.procedimento.tipo_limpeza+'>'+value.procedimento.descricao+'</option>')
            });
        }
        displayRegiao()
    })
    

    $(".adicionar_procedimento_odontologico").on('click', function(){
        let procedimentosSelecionados = $("#procedimentos_odontologico").val();
        var regiaoSelecionada;
        $("input[name='regioes_procedimentos']:checked").each(function(index, element){
            if(index == 0){
                regiaoSelecionada = $(element).val()
            }else{
                regiaoSelecionada += ','+$(element).val()
            }
        })

        if (!dentesSelecionados.length) {
            return Swal.fire("Ops!", "Selecione pelo menos um dente para o procedimento", "warning");
        } else if ($("#procedimentos_odontologico").val() == undefined) {
            return Swal.fire("Ops!", "Selecione um grupo de procedimentos", "warning");
        } else if (procedimentosSelecionados.length == 0) {
            return Swal.fire("Ops!", "Selecione um procedimento", "warning");
        } else if (exigeRegiao && !regiaoSelecionada) {
            return Swal.fire("Ops!", "Selecione uma regiao para o procedimento", "warning");
        }
        addIcon("tab-odontologico")
        // Formatando dados
        dentesSelecionados.map(function(dente) {
            procedimentosSelecionados.map(function(e, _procedimento) {
                verificaId(Math.floor(Math.random() * 256)).then((id) => {
                    let procedimento = {
                        "id": id,
                        "dente": dente,
                        "procedimento": procedimentosSelecionados[_procedimento],
                        "regiao": regiaoSelecionada,
                        "tipo": 'novo'
                    };
                    procedimentos.push(procedimento);
                    renderizaProcedimento(procedimento);
                });
            })
        });

        resetInputs();
    })

    function renderizaProcedimento(procedimento) {
        // Coloca procedimentos com nomes na visualização
        let nomeProcedimento = "";
        $.each(procedimentoOptions, function (key, procedimentoObj) {
            if(procedimento.procedimento == procedimentoObj.procedimento.id) {
                nomeProcedimento=procedimentoObj.procedimento.descricao;
            }
        });

        // Coloca regiões com nomes na visualização
        var nomeRegiao = "";
        if(procedimento.regiao != null){
            var regioes = procedimento.regiao.split(',');
            
            $("input[name='regioes_procedimentos']").map(function(_, input) {
                
                if(jQuery.inArray( $(input).val(), regioes ) != -1) {
                    nomeRegiao += $(input).attr('data-name')+" / ";
                }
            });
    
            nomeRegiao = nomeRegiao.substring(0, nomeRegiao.length - 2)
        }

        let html = $(`<tr class="procedimento" id="${procedimento.id}">
                        <td>
                            ${procedimento.dente}
                        </td>
                        <td>
                            ${nomeProcedimento}
                        </td>
                        <td>
                            ${nomeRegiao}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-outline-danger btn-sm excluir_procedimento" data-procedimento="${procedimento.id}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`
        );

        html.insertAfter("table.procedimentos tr.procedimento-base");
    }

    $("body").on("click", ".excluir_procedimento", function() {
        excluirProcedimento($(this).attr("data-procedimento"));
        if($(".excluir_procedimento").length == 0){
            removeIcon('tab-odontologico')
        }
    });

    function resetInputs() {
        renderDentalChart('canvas_dental');
        dentesSelecionados = [];
        
        $("#procedimentos_odontologico").val([]).change();

        $(".regioes").css('display', 'none');
        $(".regioes input").iCheck("uncheck");
        $(".regioes input").prop("checked", false);
    }

    function excluirProcedimento(procedimentoId) {
        _.remove(procedimentos, function(procedimento) {
            if(procedimento.tipo == 'novo'){
                return procedimento.id == procedimentoId;
            }else{
                if(procedimento.id == procedimentoId){
                    procedimento.tipo = "excluido";
                }
            }
        });        
        $(`table.procedimentos tr#${procedimentoId}`).remove();
    }

    $(".salvar-odontologico-button").on('click', function(){
        removeIcon("tab-odontologico")
        if($("#orcamento_id_edit").val()){
            editarOrcamento();
        }else{
            salvarOrcamento();
        }
    })

    function editarOrcamento(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        var orcamento_id = $("#orcamento_id_edit").val();
        if(!agendamento_id){
            agendamento_id = null;
        }

        $.ajax({
            url: "{{route('instituicao.odontologico.odontologicoEditar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('orcamento_id', orcamento_id),
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                itens: procedimentos
            },
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                $('.orcamentos-table-body').html('');
                $(".orcamento-table-footer").css('display', 'block');
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Ondotológico editado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                geraTabelaOrcamento(result)
            },
            complete: () => {
                $(".orcamento-table-footer").css('display', 'none');
                procedimentos = [];
                arrayId = [];
                $("tr.procedimento").remove();
                $("#orcamento_id_edit").val("");
                $(".cancelar-edicao-odontologico-button").css('display', 'none');
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') ;
            },
            error: function(response) {
                $(".orcamento-table-footer").css('display', 'none');
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
    }

    function salvarOrcamento() {
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();
        if(!agendamento_id){
            agendamento_id = null;
        }

        $.ajax({
            url: "{{route('instituicao.odontologico.odontologicoSalvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                itens: procedimentos
            },
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                $('.orcamentos-table-body').html('');
                $(".orcamento-table-footer").css('display', 'block');
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Ondotológico salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                geraTabelaOrcamento(result)
            },
            complete: () => {
                $(".orcamento-table-footer").css('display', 'none');
                procedimentos = [];
                arrayId = [];
                $("tr.procedimento").remove();
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
                $(".orcamento-table-footer").css('display', 'none');
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
    }

    $(".orcamentos-table-body").on('click', '.visualizar-orcamento', function(){
        var orcamento_id = $(this).attr('data-id');
        var paciente_id = $("#paciente_id").val();
        
        var url = "{{route('instituicao.odontologico.odontologicoVisualizar', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id);
        var data = {
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalVisualizar';

        $('.loading').css('display', 'block');
        $('.loading').find('.class-loading').addClass('loader')
        $('#modal-orcamentos').load(url, data, function(resposta, status) {
            $('.loading').css('display', 'none');
            $("#"+modal).modal('show');
            $('.loading').find('.class-loading').removeClass('loader')  
        });
    })
    
    $(".orcamentos-table-body").on('click', '.concluir-procedimento-odontologico', function(){
        var orcamento_id = $(this).attr('data-id');
        var paciente_id = $("#paciente_id").val();
        
        var url = "{{route('instituicao.odontologico.odontologicoConcluirProcedimento', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id);
        var data = {
            '_token': '{{csrf_token()}}'
        };
        var modal = 'modalVisualizar';

        $('.loading').css('display', 'block');
        $('.loading').find('.class-loading').addClass('loader')
        $('#modal-orcamentos').load(url, data, function(resposta, status) {
            $('.loading').css('display', 'none');
            $("#"+modal).modal('show');
            $('.loading').find('.class-loading').removeClass('loader')  
        });
    })

    $("#modal-orcamentos").on('click', '.salvar_orcamento_modal_criado', function(e){
        e.preventDefault()
        e.stopPropagation()

        var formData = new FormData($("#pagamentoCriadoOdontologico")[0]);

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $("#orcamento_id").val();

        $.ajax({
            url: "{{route('instituicao.odontologico.salvarOrcamentoFinanceiro', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                $(".orcamento-table-footer").css('display', 'block');
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Orçamento finalizado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#modalVisualizar").modal('hide');
                $('.orcamentos-table-body').html('');
                geraTabelaOrcamento(result)
                
            },
            complete: () => {
                $(".orcamento-table-footer").css('display', 'none');
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
                $(".orcamento-table-footer").css('display', 'none');
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
    })
    
    $("#modal-orcamentos").on('click', '.concluir_procedimento_odontologico_modal', function(e){
        e.preventDefault()
        e.stopPropagation()

        var formData = new FormData($("#concluirProcedimentoOdontologicoModal")[0]);

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $("#orcamento_id").val();

        $.ajax({
            url: "{{route('instituicao.odontologico.salvarOrcamentoProcedimentosAprovados', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                $(".orcamento-table-footer").css('display', 'block');
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Procedimento(s) concluido(s) com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#modalVisualizar").modal('hide');
                $('.orcamentos-table-body').html('');
                geraTabelaOrcamento(result)
                
            },
            complete: () => {
                $(".orcamento-table-footer").css('display', 'none');
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
                $(".orcamento-table-footer").css('display', 'none');
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
    })
    
    $("#modal-orcamentos").on('click', '.cancelar_aprovacao_procedimento_modal', function(e){
        e.preventDefault()
        e.stopPropagation()

        var id = $(this).attr('data-id');
        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $("#orcamento_id").val();

        Swal.fire({
            title: "Confirmar!",
            text: 'Cancelar conclusão de procedimento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('instituicao.odontologico.cancelarItemConcluidoOrcamento', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id', 'item' => 'item_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id).replace('item_id', id),
                    type: "POST",
                    data: {"_token" : "{{csrf_token()}}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                        $(".orcamento-table-footer").css('display', 'block');
                    },
                    success: (result) => {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Procedimento cancelado com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $("#modalVisualizar").modal('hide');
                        $('.orcamentos-table-body').html('');
                        geraTabelaOrcamento(result)
                        
                    },
                    complete: () => {
                        $(".orcamento-table-footer").css('display', 'none');
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                    error: function(response) {
                        $(".orcamento-table-footer").css('display', 'none');
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
            }
        })
    })
    
    $(".orcamentos-table-body").on('click', '.cancelar-aprovacao-orcamento-odontologico', function(e){
        e.preventDefault()
        e.stopPropagation()

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $(this).attr('data-id');
        
        Swal.fire({
            title: "Confirmar!",
            text: 'Cancelar aprovação do orçamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('instituicao.odontologico.cancelarOrcamentoOdontologico', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
                    type: "POST",
                    data: {"_token" : "{{csrf_token()}}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                        $(".orcamento-table-footer").css('display', 'block');
                    },
                    success: (result) => {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Orçamento cancelado com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $('.orcamentos-table-body').html('');
                        geraTabelaOrcamento(result)
                        
                    },
                    complete: () => {
                        $(".orcamento-table-footer").css('display', 'none');
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                    error: function(response) {
                        $(".orcamento-table-footer").css('display', 'none');
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
            }
        })
    })
    
    $(".orcamentos-table-body").on('click', '.excluir-orcamento-odontologico', function(e){
        e.preventDefault()
        e.stopPropagation()

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $(this).attr('data-id');
        
        Swal.fire({
            title: "Confirmar!",
            text: 'Deseja excluir o orçamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('instituicao.odontologico.excluirOrcamentoOdontologico', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
                    type: "POST",
                    data: {"_token" : "{{csrf_token()}}"},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                        $(".orcamento-table-footer").css('display', 'block');
                    },
                    success: (result) => {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Orçamento excluido com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $('.orcamentos-table-body').html('');
                        geraTabelaOrcamento(result)
                        
                    },
                    complete: () => {
                        $(".orcamento-table-footer").css('display', 'none');
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    },
                    error: function(response) {
                        $(".orcamento-table-footer").css('display', 'none');
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
            }
        })
    })

    $(".orcamentos-table-body").on('click', '.editar-orcamento-odontologico', function(e){
        e.preventDefault()
        e.stopPropagation()

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $(this).attr('data-id');

        $.ajax({
            url: "{{route('instituicao.odontologico.editarOrcamentoOdontologico', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: {"_token" : "{{csrf_token()}}"},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $("#orcamento_id_edit").val(result[0].odontologico_paciente_id)
                $(".cancelar-edicao-odontologico-button").css('display', 'block');
                procedimentos = [];
                arrayId = [];
                
                $("tr.procedimento").remove();
                result.map(function(e, orcamento) {
                    arrayRegiao = '';
                    if(result[orcamento]['regiao_procedimento'].length > 0){
                        for (let index = 0; index < result[orcamento]['regiao_procedimento'].length; index++) {
                            const element = result[orcamento]['regiao_procedimento'][index]['id'];
                            if (index == 0) {
                                arrayRegiao = ''+element+'';
                            } else {
                                arrayRegiao += ','+element
                            }
                        }
                    }
                    let procedimento = {
                        "id": result[orcamento].id,
                        "dente": result[orcamento].dente_id,
                        "procedimento": result[orcamento].procedimento_instituicao_convenio_id,
                        "regiao": (arrayRegiao != "") ? arrayRegiao : result[orcamento].regiao_procedimento_id,
                        "tipo": 'editando'
                    };
                    procedimentos.push(procedimento);
                    renderizaProcedimentoEdit(procedimento, result[orcamento].procedimentos_itens.descricao);
                })
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
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
    })

    function renderizaProcedimentoEdit(procedimento, nomeProcedimento) {
        // Coloca procedimentos com nomes na visualização

        // Coloca regiões com nomes na visualização
        var nomeRegiao = "";
        if(procedimento.regiao != null){
            var regioes = procedimento.regiao.split(',');
            
            $("input[name='regioes_procedimentos']").map(function(_, input) {
                
                if(jQuery.inArray( $(input).val(), regioes ) != -1) {
                    nomeRegiao += $(input).attr('data-name')+" / ";
                }
            });
    
            nomeRegiao = nomeRegiao.substring(0, nomeRegiao.length - 2)
        }

        let html = $(`<tr class="procedimento editando" id="${procedimento.id}">
                        <td>
                            ${procedimento.dente}
                        </td>
                        <td>
                            ${nomeProcedimento}
                        </td>
                        <td>
                            ${nomeRegiao}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-outline-danger btn-sm excluir_procedimento" data-procedimento="${procedimento.id}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`
        );

        html.insertAfter("table.procedimentos tr.procedimento-base");
    }
    
    $(".cancelar-edicao-odontologico-button").on('click', function(){
        procedimentos = [];
        arrayId = [];
        $("tr.procedimento").remove();
        $("#orcamento_id_edit").val("");
        $(".cancelar-edicao-odontologico-button").css('display', 'none');
    })

    function liberaImprimirOdontologico(id){
        var ids_convenio = $("#itens_aprovados").val();
        var paciente_id = $("#paciente_id").val();
        var url = "{{route('agendamento.odontologico.imprimirOrcamento', ['paciente' => 'paciente_id','orcamento' => 'item'])}}".replace('paciente_id', paciente_id).replace('item', id)
        url = url+'?ids_convenio='+ids_convenio
        
        newPopup(url);
    }
    function liberaImprimirOdontologicoTotal(id){
        var ids_convenio = $("#itens_aprovados").val();
        var paciente_id = $("#paciente_id").val();
        var url = "{{route('agendamento.odontologico.imprimirOrcamentoTotal', ['paciente' => 'paciente_id','orcamento' => 'item'])}}".replace('paciente_id', paciente_id).replace('item', id)
        url = url+'?ids_convenio='+ids_convenio
        
        newPopup(url);
    }
    function contratoOdontologico(id){
        var paciente_id = $("#paciente_id").val();
        var url = "{{route('agendamento.odontologico.contratoOrcamento', ['paciente' => 'paciente_id','orcamento' => 'item'])}}".replace('paciente_id', paciente_id).replace('item', id)
        newPopup(url);
    }

    async function verificaId(id){
        while (arrayId.includes(id)) {
            id = Math.floor(Math.random() * 256);
        }
        arrayId.push(id)
        return id;        
    }
    
</script>

