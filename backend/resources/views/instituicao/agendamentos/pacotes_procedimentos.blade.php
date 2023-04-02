
    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Procedimentos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
  
    <div class="row modal-body" id="">
        <div class="col-sm form-group">
            <label class="form-label">Pacotes:</label>
            <select class="form-control" name="pacote" style="width: 100%;">
                <option value="" data-pacote="">Selecione</option>
                @foreach($pacotes as $item)
                    <option data-pacote="{{$item}}">{{$item->descricao}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-12">
            <div class="row" id="selectVariosProc">
                <div class="col-sm form-group">
                    <input type="search" class="form-control" name="busca" placeholder="Procurar procedimento" autocomplete="off">
                </div>
                @foreach($procedimentos as $item)
                    <div class="col-sm-12 proc_btn_hidden" style="margin-bottom: 5px;">
                        <div class="btn-group col-sm mx-0" data-toggle="buttons">
                            @php
                                $item_proc = $item->instituicaoProcedimentosConvenios[0]->pivot;
                            @endphp

                    
                            {{-- <label class="btn btn-outline-primary btn-proc">
                                <input type="checkbox" class="checkProc btn-check" value="{{$item_proc->id}}" id="proc_{{$item->procedimento->id}}" data-convenio_id="{{$item_proc->convenios_id}}" data-valor="{{$item_proc->valor}}">{{$item->procedimento->descricao}}                                                    
                            </label> --}}

                            <label class="btn btn-outline-primary btn-proc" data-id="{{$item_proc->id}}" id="proc_{{$item->procedimento->id}}" data-convenio_id="{{$item_proc->convenios_id}}" data-valor="{{$item_proc->valor}}">
                                {{-- <input type="checkbox" class="btn-check checkProc" value="{{$item_proc->id}}" id="proc_{{$item->procedimento->id}}" data-convenio_id="{{$item_proc->convenios_id}}" data-valor="{{$item_proc->valor}}"> --}}
                                {{$item->procedimento->descricao}}
                            </label>
                        </div>
                    </div>
                    
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal-footer" style="width: 100%">
        <button type="button" class="btn btn-default waves-effect close" data-dismiss="modal">Fechar</button>
      
        <button type="button" class="btn btn-secondary waves-effect waves-light selectProc" style="background-color: #009688;border:#009688;color: #fff;">
            <span class="btn-label"><i class="fa fa-check"></i></span>Escolher proceimentos selecionados
        </button>
        {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Salvar</button> --}}
    </div>

    <script>
        $(document).ready(function(){
            $('.convenio_procedimentos_agendar .procedimentos').each(function(index, element){
                $("#"+$(element).data('proc-id')).addClass('active');
            })
        })

        $(".selectProc").on("click", function(){
            $("#selectVariosProc").find(".btn-proc").each((index, element) => {
                if($(element).is(".active")){
                    quantidade_convenio++;

                    procedimento_id = $(element).data('id');
                    convenio_id = $(element).data('convenio_id')
                    valor = $(element).data('valor')
                    proc_id = $(element).attr('id')

                    $($('#item-convenio-agendar').html()).insertBefore(".add-class");

                    $('.mask_item').setMask();
                    $('.mask_item').removeClass('mask_item');
                    $(".selectfild2").select2();

                    $("[name^='convenio[#]']").each(function(index, element) {
                        const name = $(element).attr('name');

                        $(element).attr('name', name.replace('#',quantidade_convenio));

                        // console.log(element);
                    })

                    $("[name^='convenio["+quantidade_convenio+"][convenio_agenda]']").val(convenio_id).change();
                    $("[name^='convenio["+quantidade_convenio+"][procedimento_agenda]']").attr('data-val', procedimento_id);
                    $("[name^='convenio["+quantidade_convenio+"][procedimento_agenda]']").attr('data-valor', valor.replace('.', ','));
                    $("[name^='convenio["+quantidade_convenio+"][procedimento_agenda]']").attr('data-proc-id', proc_id);
                }              
                
            })

            $('#modalPacotesProcedimentos').modal('hide');
            $('#modalInserirAgenda').modal('show');
        })

        $(".close").on("click", function(){ 
            $('#modalPacotesProcedimentos').modal('hide');
            $('#modalInserirAgenda').modal('show');
        })

        $("[name^='pacote']").select2();

        $("[name^='pacote']").on('change', function(){
            Procedimentos_pacotes = $(this).find('option').filter(':selected').data('pacote').procedimento_vinculo;

            for(i = 0; i < Procedimentos_pacotes.length; i++) {
                $("#proc_"+Procedimentos_pacotes[i].pivot.procedimento_id).addClass('active')
            }  
        })

        $("[name^='busca']").keyup(function(){
            busca = $(this).val().toLowerCase();

            $("#selectVariosProc").find(".btn-proc").each((index, element) => {
                console.log($(element).text().trim(), busca, $(element).text().trim().includes(busca));

                if($(element).text().trim().toLowerCase().includes(busca)){
                    $(element).parents('.proc_btn_hidden').css('display', 'block');
                }else{
                    $(element).parents('.proc_btn_hidden').css('display', 'none');
                }

            })


        })

    </script>