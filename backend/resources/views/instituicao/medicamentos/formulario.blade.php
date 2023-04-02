@csrf

<div class="row">
    <div class="col-md-6 form-group">
        <input class="with-gap form-control" name="tipo" type="radio" id="industrializado" value="industrializado" checked />
        <label for="industrializado" class="form-control-label">Medicamento industrializado</label>
    </div>
    <div class="col-md-6 form-group ">
        <input class="with-gap form-control" name="tipo" type="radio" id="manipulado" value="manipulado"/>
        <label for="manipulado" class="form-control-label">Medicamento manipulado</label>
    </div>
    <hr style="width: 90%">

    <div class="col-md-4 form-group">
        <label class="form-control-label">Nome *</label>
        <input type="text" name="nome" value="" class="form-control">
    </div>
    
    <div class="col-md-2 form-group">
        <label class="form-control-label">Concentração</label>
        <input type="text" name="concentracao" value="" class="form-control" placeholder="Ex.: 10 mg/ml, 100 mg, 1000UI">
    </div>
    
    <div class="col-md-2 form-group">
        <label class="form-control-label">Forma farmacêutica:</label>
        <input type="text" name="forma_farmaceutica" value="" class="form-control" placeholder="Ex.: Comprimido, gel, xarope...">
    </div>
    
    <div class="col-md-2 form-group">
        <label class="form-control-label">Via de administração: *</label>
        <select class="form-control selectfield2Composicao" name="via_administracao" id="via_administracao" style="width: 100%">
            <option value="1">Oral</option>
            <option value="2">Sublingual</option>
            <option value="3">Retal</option>
            <option value="4">Bucal</option>
            <option value="5">Gástrica</option>
            <option value="6">Duodenal</option>
            <option value="7">Nasal</option>
            <option value="8">Ocular</option>
            <option value="9">Vaginal</option>
            <option value="10">Uretral e peniana</option>
            <option value="11">Transdérmica</option>
            <option value="12">Cutânea</option>
            <option value="13">Pulmonar</option>
            <option value="14">Tópico</option>
            <option value="15">Intradérmica</option>
            <option value="16">Intramuscular</option>
            <option value="17">Intra-arterial</option>
            <option value="18">Intratecal</option>
            <option value="19">Intraperitoneal</option>
            <option value="20">Intrapleural</option>
            <option value="21">Intravesical</option>
            <option value="22">Intra-articular</option>
            <option value="23">Intraraquídea</option>
            <option value="24">Intra-óssea</option>
            <option value="25">Intracardíaca</option>
        </select>
    </div>

    <div class="form-group col-md-2">
        <label class="form-control-label">Quantidade (referencia usuario)</span></label>
        <input type="text" class="form-control " name="quantidade">
    </div>

    <div class="form-group col-md-12">
        <label class="form-control-label">Posologia (referencia usuario)</span></label>
        <textarea class="form-control " name="posologia" cols="2" rows="2"></textarea>
    </div>

    <hr style="width: 90%">
    <div class="col-md-12" style="text-align: center">
        <h5>Informa abaixo as substâncias que compõem o medicamento.
            <span class="text-warning" style="font-size: 12px;">(Não obrigatório para medicamentos industrializados)</span>
        </h5>
    </div>
    <hr style="width: 90%">
    <div class="col-md-12 adicionar_composicao_texto" style="text-align: center">
        <h3>Não existe composição para este medicamento.</h3>
        <button type="button" class="btn btn-info waves-effect adicionar_composicao_button">Adicionar</button>
    </div>

    <div class="col-md-12 adicionar_composicao_campo" style="display: none">
        <div class="composicao_medicamento_itens row">
            <div class="form-group col-md-12 add-class-composicao" >
                <span alt="default" class="add-composicao fas fa-plus-circle">
                    <a class="mytooltip" href="javascript:void(0)">
                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar composição"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".selectfield2Composicao").select2()
    })

    var quantidade_composicao = 0;

    $("#modalMedicamento").find("[name='tipo']").on('change', function() {
        if($("[name='tipo']:checked").val() == 'manipulado'){
            if($('.composicao_medicamento_itens').find('.item-composicao-medicamento').length == 0){
                addMedicamentoComposicao();
                $(".adicionar_composicao_texto").css('display', 'none')
                $(".adicionar_composicao_campo").css('display', 'block')
            }
        }
    })

    $('.composicao_medicamento_itens').on('click', '.add-composicao', function(){
        addMedicamentoComposicao();
    });
    
    $('.adicionar_composicao_button').on('click', function(){
        addMedicamentoComposicao();
        $(".adicionar_composicao_texto").css('display', 'none')
        $(".adicionar_composicao_campo").css('display', 'block')
    });

    function addMedicamentoComposicao(){
        quantidade_composicao++;
        
        $($('#item-composicao-medicamento').html()).insertBefore(".add-class-composicao");

        $("[name^='composicoes[#]']").each(function(index, element) {
            const name = $(element).attr('name');

            $(element).attr('name', name.replace('#',quantidade_composicao));
        })
    }

    $('.composicao_medicamento_itens').on('click', '.item-composicao-medicamento .remove-composicao', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item-composicao-medicamento').remove();
        if($("[name='tipo']:checked").val() == 'manipulado'){
            if ($('.composicao_medicamento_itens').find('.item-composicao-medicamento').length == 0) {
                quantidade_composicao = 0;
                addMedicamentoComposicao();
            }
        }else{
            if ($('.composicao_medicamento_itens').find('.item-composicao-medicamento').length == 0) {
                quantidade_composicao = 0;
                $(".adicionar_composicao_texto").css('display', 'block')
                $(".adicionar_composicao_campo").css('display', 'none')
            }
        }

    });

</script>


<script type="text/template" id="item-composicao-medicamento">
    <div class="col-md-12 item-composicao-medicamento">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-composicao">(remover)</a>
            </div>
            <div class="form-group dados_parcela col-md-8">
                <label class="form-control-label">Substancia *:</label>
                <input type="text" name="composicoes[#][substancia]" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Concentração *</span></label>
                <input type="text" class="form-control" name="composicoes[#][concentracao]" placeholder="Ex.: 10 mg/ml, 100 mg, 1000UI">
            </div>
        </div>
    </div>
</script>