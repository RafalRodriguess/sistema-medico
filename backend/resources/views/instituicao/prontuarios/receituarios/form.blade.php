<div class="col-md-12">
    <div class="medicamentos_itens row">
        @if (empty($medicamentosReceituario))
            <div class="col-md-12 item-medicamento">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-medicamento">(remover)</a>
                    </div>
                    <div class="form-group col-md-10">
                        <label class="form-control-label">Medicamento *
                            <span alt="default" class="addMedicamento fas fa-plus-circle" style="cursor: pointer;" onclick="cadastrarMedicamento()">
                                <a class="mytooltip" href="javascript:void(0)" >
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cadastrar medicamento"></i>
                                </a>
                            </span>
                        </label>
                        <select name="medicamentos[0][medicamento]" class="form-control selectfild2Medicamento" style="width: 100%" onchange="getMedicamento(this)">
                            <option value="">Selecione um medicamento</option>
                            @foreach ($medicamentos as $item)
                                <option value="{{$item->id}}" data-tipo="{{$item->tipo}}">{{$item->nome}} @if ($item->concentracao || $item->forma_farmaceutica)
                                    ({{($item->concentracao) ? $item->concentracao : ""}} @if ($item->concentracao && $item->forma_farmaceutica)
                                    -
                                    @endif {{($item->forma_farmaceutica) ? $item->forma_farmaceutica : ""}})
                                @endif </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="form-control-label">Quantidade *</span></label>
                        <input type="text" class="form-control" name="medicamentos[0][quantidade]">
                    </div>
                    <div class="col-md-3"></div>
                    <div class="row col-md-6 adicionar_composicao_campo" style="display: none">
                        <div class="card">
                            <div class="card-body">
                                <h5>Composição</h5>
                                <div class="composicao_medicamento_itens_receituario row">
                                    <div class="form-group col-md-12 add-class-composicao-0" >
                                        <span alt="default" class="add-composicao-receituario fas fa-plus-circle" onclick="addMedicamentoComposicaoReceituario(0)">
                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar composição"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">Posologia</span></label>
                        <textarea class="form-control " name="medicamentos[0][posologia]" cols="2" rows="2"></textarea>
                    </div>
                </div>
                <hr style="width: 100%">
            </div>
        @else

            @for($i = 0, $max = count($medicamentosReceituario); $i < $max; $i++)
                <div class="col-md-12 item-medicamento">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascrit:void(0)" class="small remove-medicamento">(remover)</a>
                        </div>
                        <div class="form-group col-md-10">
                           <label class="form-control-label">Medicamento *
                                <span alt="default" class="addMedicamento fas fa-plus-circle" style="cursor: pointer;" onclick="cadastrarMedicamento()">
                                    <a class="mytooltip" href="javascript:void(0)" >
                                        <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cadastrar medicamento"></i>
                                    </a>
                                </span>
                           </label>
                           <select name="medicamentos[{{$i}}][medicamento]" class="form-control selectfild2Medicamento" style="width: 100%" onchange="getMedicamento(this)">
                                <option value="">Selecione um medicamento</option>
                                @foreach ($medicamentos as $item)
                                    <option value="{{$item->id}}" @if ($item->id == $medicamentosReceituario[$i]->id)
                                        selected
                                    @endif data-tipo="{{$item->tipo}}">{{$item->nome}} ({{$item->concentracao}} - {{$item->forma_farmaceutica}})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label class="form-control-label">Quantidade *</span></label>
                            <input type="text" class="form-control " name="medicamentos[{{$i}}][quantidade]">
                        </div>

                        <div class="col-md-3"></div>
                        <div class="row col-md-6 adicionar_composicao_campo" style="display: none">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Composição</h5>
                                    <div class="composicao_medicamento_itens_receituario row">
                                        <div class="form-group col-md-12 add-class-composicao-{{$i}}" >
                                            <span alt="default" class="add-composicao-receituario fas fa-plus-circle">
                                                <a class="mytooltip" href="javascript:void(0)">
                                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar composição"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="form-control-label">Posologia</span></label>
                            <textarea class="form-control " name="medicamentos[{{$i}}][posologia]" cols="2" rows="2"></textarea>
                        </div>
                    </div>
                    <hr style="width: 100%">
                </div>
            @endfor
        @endif
        

        <div class="form-group col-md-12 add-class-medicamento" >
            <span alt="default" class="add-medicamento fas fa-plus-circle">
                <a class="mytooltip" href="javascript:void(0)">
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar medicamento"></i>
                </a>
            </span>
        </div>
    </div>
</div>


<script>
    var medicamentosAdicionados = [];
    var quantidade_medicamento = 0;
    var quantidade_composicao_receituario = 0;

    $(document).ready(function(){
        quantidade_medicamento = $('.item-medicamento').length;
        $(".selectfild2Medicamento").select2()
        $("[data-toggle='tooltip']").tooltip()
    })
    
    $('.medicamentos_itens').on('click', '.add-medicamento', function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        addMedicamento();
    });

    function addMedicamento(){
        quantidade_medicamento++;
        
        $($('#item-medicamento').html()).insertBefore(".add-class-medicamento");

        $('.mask_item').setMask();
        $('.mask_item').removeClass('mask_item');
        $("[data-toggle='tooltip']").tooltip()
        $(".selectfild2Medicamento").select2();

        $("[name^='medicamentos[#]']").each(function(index, element) {
            const name = $(element).attr('name');

            $(element).attr('name', name.replace('#',quantidade_medicamento));

            for (let index = 0; index < medicamentosAdicionados.length; index++) {
                const value = medicamentosAdicionados[index];
                $(element).append('<option value='+value.id+'>'+value.nome+' ('+value.concentracao+' - '+value.forma_farmaceutica+')</option>')
            }
            $(element).parents(".item-medicamento").find('.add-class-composicao').addClass('add-class-composicao-'+quantidade_medicamento);
            $(element).parents(".item-medicamento").find('.add-class-composicao').attr('onclick', 'addMedicamentoComposicaoReceituario('+quantidade_medicamento+')');
            $(element).parents(".item-medicamento").find('.add-class-composicao').removeClass('add-class-composicao');
            $("[name^='medicamentos["+quantidade_medicamento+"][medicamento']").attr('onchange', 'getMedicamento(this)');
        })
    }

    $('.medicamentos_itens').on('click', '.item-medicamento .remove-medicamento', function(e){
        e.preventDefault()
        e.stopImmediatePropagation()

        $(e.currentTarget).parents('.item-medicamento').remove();
        if ($('.medicamentos_itens').find('.item-medicamento').length == 0) {
            quantidade_medicamento = 0;
            addMedicamento();
        }

    });

    function cadastrarMedicamento(){
        $("#modalMedicamento").modal('show')
    }

    function getMedicamento(element){
        var medicamento_id = $(element).val()

        $(element).parents(".item-medicamento").find('.adicionar_composicao_campo').find(".composicao_medicamento_itens_receituario").find('.item-composicao-medicamento').remove();
        if($(element).find('option:selected').attr('data-tipo') == "manipulado"){
            
            $(element).parents(".item-medicamento").find(".adicionar_composicao_campo").css('display', 'block');

            $.ajax({
                url: "{{route('agendamento.receituario.getComposicaoMedicamento', ['medicamento' => 'medicamento_id'])}}".replace('medicamento_id', medicamento_id),
                type: 'get',
                beforeSend: () => {
                    
                },
                success: function(result) {
                    posicao = $(element).attr('name').split("").filter(n => (Number(n) || n == 0)).join("")
                    for (let index = 0; index < result.composicao.length; index++) {

                        quantidade_composicao_receituario++;
            
                        $($('#item-composicao-medicamento-receituario').html()).insertBefore(".add-class-composicao-"+posicao);

                        $("[name^='composicoes[#]']").each(function(index, element) {
                            const name = $(element).attr('name');

                            $(element).attr('name', name.replace('#', posicao+"]["+quantidade_composicao_receituario));
                        })

                        $("[name^='composicoes["+posicao+"]["+quantidade_composicao_receituario+"][substancia']").val(result.composicao[index]['substancia'])
                        $("[name^='composicoes["+posicao+"]["+quantidade_composicao_receituario+"][concentracao']").val(result.composicao[index]['concentracao'])
                    }

                    if(result.usuario.length > 0){
                        $("[name^='medicamentos["+posicao+"][quantidade']").val(result.usuario[0].pivot.quantidade)
                        $("[name^='medicamentos["+posicao+"][posologia']").val(result.usuario[0].pivot.posologia)
                    }
                },
                complete: () => {
                    
                }
            });

        }else{
            $(element).parents(".item-medicamento").find(".adicionar_composicao_campo").css('display', 'none');
            
            $.ajax({
                url: "{{route('agendamento.receituario.getComposicaoMedicamento', ['medicamento' => 'medicamento_id'])}}".replace('medicamento_id', medicamento_id),
                type: 'get',
                beforeSend: () => {
                    
                },
                success: function(result) {
                    
                    posicao = $(element).attr('name').split("").filter(n => (Number(n) || n == 0)).join("")

                    if(result.usuario.length > 0){
                        $("[name^='medicamentos["+posicao+"][quantidade']").val(result.usuario[0].pivot.quantidade)
                        $("[name^='medicamentos["+posicao+"][posologia']").val(result.usuario[0].pivot.posologia)
                    }
                },
                complete: () => {
                    
                }
            });
        }
    }

    // $('.composicao_medicamento_itens_receituario').on('click', '.add-composicao-receituario', function(){
    //     addMedicamentoComposicaoReceituario();
    // });

    function removerComposicao(e){
        
        $(e).parents('.item-composicao-medicamento').remove();
    };

    function addMedicamentoComposicaoReceituario(posicao){
        quantidade_composicao_receituario++;
        
        $($('#item-composicao-medicamento-receituario').html()).insertBefore(".add-class-composicao-"+posicao);

        $("[name^='composicoes[#]']").each(function(index, element) {
            const name = $(element).attr('name');

            $(element).attr('name', name.replace('#', posicao+"]["+quantidade_composicao_receituario));
        })
    }

    $(".salvar_formulario_medicamentos").on('click', function(e){
        e.preventDefault()
        e.stopPropagation()

        var formData = new FormData($("#novoMedicamento")[0])

        $.ajax({
            url: "{{route('agendamento.receituario.cadastrarMedicamento')}}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Medicamento salvo com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $('#novoMedicamento').each (function(){
                    this.reset();
                });
                $("#modalMedicamento").modal('hide')
                $("[name$='[medicamento]']").each(function(index, element) {
                    $(element).append('<option value='+result.id+'>'+result.nome+' ('+result.concentracao+' - '+result.forma_farmaceutica+')</option>')
                })
                medicamentosAdicionados.push(result);
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
    });
</script>

