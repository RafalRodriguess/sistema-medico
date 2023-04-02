<div class="p-10">
    <input type="hidden" name="in_page_sangues_derivados" value="1">
    <div class="row">
        <div class="col-md-12 sangues_derivados">
            <h4>Sangues e Derivados</h4>
            @if (count($agendamento->sangueDerivados) > 0)
                @foreach ($agendamento->sangueDerivados as $key => $item)
                    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
                        <div class="col-md-12">
                            <a href="javascrit:void(0)" class="small remove_sangues_derivados">(remover)</a>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="centro_cirurgico_editar" class="control-label">Sangue e derivados *:</label>
                            <select class="form-control select2sd" name="sangues_derivados[{{$key}}][sangue_derivado]" id="sangues_derivados_id_{{$key}}" style="width: 100%">
                                @foreach ($sangues_derivados as $sangue_derivado)
                                    <option value="{{$sangue_derivado->id}}" @if ($sangue_derivado->id == $item->id)
                                        selected
                                    @endif>{{$sangue_derivado->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
                            <input type="integer" class="form-control item_sd" id="sangues_derivados_quantidade_{{$key}}" name="sangues_derivados[{{$key}}][quantidade]" alt="numeric" value="{{$item->pivot->quantidade}}">
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="form-group col-md-12 add-class-sangues-derivados" style="margin-top: 10px">
                <span alt="default" class="add_sangues_derivados fas fa-plus-circle" style="cursor: pointer;">
                    <a class="mytooltip" href="javascript:void(0)">
                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar sangue e derivados"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>


<script>
    
    var quantidade_sangues_derivados;

    $(document).ready(function() {
        quantidade_sangues_derivados = $('.sangues_derivados').find('.item').length
        $("[data-toggle='tooltip']").tooltip()
    })

    $('.sangues_derivados').on('click', '.item .remove_sangues_derivados', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item').remove();
    });

    $('.sangues_derivados').on('click', '.add_sangues_derivados', function(){
        // $('.formula').append($($('#item-formula').html()).insertBefore(".add-class"));
        $($('#item-sangues-derivados').html()).insertBefore(".add-class-sangues-derivados");

        $('.item_sd').setMask();
        $('.item_sd').removeClass('item_sd');
        // $(".select2sd").select2();

        $("[name^='sangues_derivados[#]']").each(function(index, element) {
            const name = $(element).attr('name');
            $(element).attr('name', name.replace('#',quantidade_sangues_derivados));
            const ids = $(element).attr('id');
            $(element).attr('id', ids.replace('#',quantidade_sangues_derivados));
        })

        $("#sangues_derivados_id_"+quantidade_sangues_derivados).select2()
        
        quantidade_sangues_derivados++;  
    });
</script>

<script type="text/template" id="item-sangues-derivados">
    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
        <div class="col-md-12">
            <a href="javascrit:void(0)" class="small remove_sangues_derivados">(remover)</a>
        </div>
        <div class="form-group col-md-8">
            <label for="centro_cirurgico_editar" class="control-label">Sangue e derivados *:</label>
            <select class="form-control select2sd" name="sangues_derivados[#][sangue_derivado]" id="sangues_derivados_id_#" style="width: 100%">
                <option value="">Selecione</option>
                @foreach ($sangues_derivados as $sangue_derivado)
                    <option value="{{$sangue_derivado->id}}">{{$sangue_derivado->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Quantidade:</label>
            <input type="integer" alt="numeric" class="form-control item_sd" id="sangues_derivados_quantidade_#" name="sangues_derivados[#][quantidade]" alt="numeric" value="0">
        </div>
    </div>
</script>