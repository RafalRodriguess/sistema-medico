<div class="p-10">
    <input type="hidden" name="in_page_outras_cirurgias" value="1">
    <div class="row">
        <div class="col-md-12 outras_cirurgias">
            <h4>Outras cirúrgias</h4>
            @if (count($agendamento->outrasCirurgias) > 0)
                @foreach ($agendamento->outrasCirurgias as $key => $item)
                    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
                        <div class="col-md-12">
                            <a href="javascrit:void(0)" class="small remove_outras_cirurgias">(remover)</a>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Cirurgias *:</label>
                            <select class="form-control select2oc" name="outras_cirurgias[{{$key}}][cirurgia]" id="outras_cirurgias_cirurgia_{{$key}}" style="width: 100%" onchange="getDadosCirurgia(this)">
                                @foreach ($cirurgias as $cirurgia)
                                    <option value="{{$cirurgia->id}}" @if ($cirurgia->id == $item->id)
                                        selected
                                    @endif data-via="{{$cirurgia->via_acesso_id}}" data-tempo="{{$cirurgia->previsao}}">{{$cirurgia->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Vias acesso *:</label>
                            <select class="form-control select2oc" name="outras_cirurgias[{{$key}}][via_acesso]" id="outras_cirurgias_vai_acesso_{{$key}}" style="width: 100%">
                                @foreach ($vias_acesso as $via_acesso)
                                    <option value="{{$via_acesso->id}}" @if ($via_acesso->id == $item->pivot->via_acesso_id)
                                        selected
                                    @endif>{{$via_acesso->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Convênio *:</label>
                            <select class="form-control select2oc" name="outras_cirurgias[{{$key}}][convenio]" id="outras_cirurgias_convenio_{{$key}}" style="width: 100%">
                                @foreach ($convenios as $convenio)
                                    <option value="{{$convenio->id}}" @if ($convenio->id == $item->pivot->convenio_id)
                                        selected
                                    @endif>{{$convenio->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Cirurgião *:</label>
                            <select class="form-control select2oc" name="outras_cirurgias[{{$key}}][medico]" id="outras_cirurgias_medico_{{$key}}" style="width: 100%">
                                @foreach ($medicos as $medico)
                                    <option value="{{$medico->id}}" @if ($medico->id == $item->pivot->cirurgiao_id)
                                        selected
                                    @endif>{{$medico->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Pacote *:</label>
                            <select name="outras_cirurgias[{{$key}}][pacote]" id="outras_cirurgias_pacote_{{$key}}" class="form-control select2editar" style="width: 100%">
                                <option value="0" @if ($item->pivot->pacote == '0')
                                        selected
                                    @endif>Não</option>
                                <option value="1" @if ($item->pivot->pacote == '1')
                                        selected
                                    @endif>Sim</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="centro_cirurgico_editar" class="control-label">Tempo (min) *:</label>
                            <input type="text" class="form-control item_oc" id="outras_cirurgias_tempo_{{$key}}" name="outras_cirurgias[{{$key}}][tempo]" alt="numeric" value="{{$item->pivot->tempo}}">
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="form-group col-md-12 add-class-outras-cirurgias" style="margin-top: 10px">
                <span alt="default" class="add_outras_cirurgias fas fa-plus-circle" style="cursor: pointer;">
                    <a class="mytooltip" href="javascript:void(0)">
                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar outra cirúrgia"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>


<script>
    
    var quantidade_outras_cirurgias;

    $(document).ready(function() {
        quantidade_outras_cirurgias = $('.outras_cirurgias').find('.item').length
        $("[data-toggle='tooltip']").tooltip()
    })

    $('.outras_cirurgias').on('click', '.item .remove_outras_cirurgias', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item').remove();
    });

    $('.outras_cirurgias').on('click', '.add_outras_cirurgias', function(){
        // $('.formula').append($($('#item-formula').html()).insertBefore(".add-class"));
        $($('#item-outras-cirurgias').html()).insertBefore(".add-class-outras-cirurgias");

        $('.item_oc').setMask();
        $('.item_oc').removeClass('item_oc');
        // $(".select2ocs").select2();

        $("[name^='outras_cirurgias[#]']").each(function(index, element) {
            const name = $(element).attr('name');
            $(element).attr('name', name.replace('#',quantidade_outras_cirurgias));
            const ids = $(element).attr('id');
            $(element).attr('id', ids.replace('#',quantidade_outras_cirurgias));
        })

        $("#outras_cirurgias_cirurgia_"+quantidade_outras_cirurgias).select2()
        $("#outras_cirurgias_vai_acesso_"+quantidade_outras_cirurgias).select2()
        $("#outras_cirurgias_convenio_"+quantidade_outras_cirurgias).select2()
        $("#outras_cirurgias_medico_"+quantidade_outras_cirurgias).select2()
        
        quantidade_outras_cirurgias++;  
    });

    function getDadosCirurgia(element){
        if($(element).val() != ""){
            posicao = $(element).attr('name').split("").filter(n => (Number(n) || n == 0)).join("")
            via_id = $("#outras_cirurgias_cirurgia_"+posicao+" option:selected").attr('data-via');
            tempo = $("#outras_cirurgias_cirurgia_"+posicao+" option:selected").attr('data-tempo');
            
            $("#outras_cirurgias_vai_acesso_"+posicao).val(via_id).change()
            $("#outras_cirurgias_tempo_"+posicao).val(tempo)
        }
    }
</script>

<script type="text/template" id="item-outras-cirurgias">
    <div class="row item" style="border-bottom: dashed 1px #00000061; margin-top: 10px">
        <div class="col-md-12">
            <a href="javascrit:void(0)" class="small remove_outras_cirurgias">(remover)</a>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Cirurgias *:</label>
            <select class="form-control select2oc" name="outras_cirurgias[#][cirurgia]" id="outras_cirurgias_cirurgia_#" style="width: 100%" onchange="getDadosCirurgia(this)">
                <option value="">Selecione uma cirurgia</option>
                @foreach ($cirurgias as $cirurgia)
                    <option value="{{$cirurgia->id}}" data-via="{{$cirurgia->via_acesso_id}}" data-tempo="{{$cirurgia->previsao}}">{{$cirurgia->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Vias acesso *:</label>
            <select class="form-control select2oc" name="outras_cirurgias[#][via_acesso]" id="outras_cirurgias_vai_acesso_#" style="width: 100%">
                @foreach ($vias_acesso as $via_acesso)
                    <option value="{{$via_acesso->id}}">{{$via_acesso->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Convênio *:</label>
            <select class="form-control select2oc" name="outras_cirurgias[#][convenio]" id="outras_cirurgias_convenio_#" style="width: 100%">
                @foreach ($convenios as $convenio)
                    <option value="{{$convenio->id}}">{{$convenio->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Cirurgião *:</label>
            <select class="form-control select2oc" name="outras_cirurgias[#][medico]" id="outras_cirurgias_medico_#" style="width: 100%">
                @foreach ($medicos as $medico)
                    <option value="{{$medico->id}}">{{$medico->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Pacote *:</label>
            <select name="outras_cirurgias[#][pacote]" id="outras_cirurgias_pacote_#" class="form-control select2editar" style="width: 100%">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Tempo (min) *:</label>
            <input type="text" class="form-control item_oc" id="outras_cirurgias_tempo_#" name="outras_cirurgias[#][tempo]" alt="numeric" value="0">
        </div>
    </div>
</script>