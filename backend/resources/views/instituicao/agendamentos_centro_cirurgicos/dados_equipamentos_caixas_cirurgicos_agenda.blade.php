<div class="p-10">
    <input type="hidden" name="in_page_equipamentos_caixas_cirurgicas" value="1">
    <div class="row">
        <div class="col-md-6 equipamentos" style="border-right: dashed 1px #00000061;">
            <h4>Equipamentos</h4>
            @include('instituicao.agendamentos_centro_cirurgicos.editar.equipamentos_editar')
        </div>
        <div class="col-md-6 caixas_cirurgicas">
            <h4>Caixas Cirúrgicas</h4>
            @include('instituicao.agendamentos_centro_cirurgicos.editar.caixas_cirurgicas_editar')
        </div>
    </div>
</div>


<script>
    
    var quantidade_caixa_cirurgica;
    var quantidade_equipamento;

    $(document).ready(function() {
        quantidade_equipamento = $('.equipamentos').find('.item').length
        quantidade_caixa_cirurgica = $('.caixas_cirurgicas').find('.item').length
        $("[data-toggle='tooltip']").tooltip()
    })
</script>