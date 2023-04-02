@extends('instituicao.layout')
{{--

@push('estilos')
    <style>
        .addMedicamento{
            display: none;
        }
    </style>
@endpush --}}

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Modelo de receituário #{$modelo->id} {$modelo->descricao}",
        'breadcrumb' => [
            'Modelo de receituário' => route('instituicao.modeloReceituario.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modeloReceituario.update', [$modelo]) }}" method="post">
                @csrf
                @method('put')
               <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('instituicao_prestador_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prestador</label>
                        <select class="form-control select2 @if($errors->has('instituicao_prestador_id')) form-control-danger @endif" name="instituicao_prestador_id" id="instituicao_prestador_id" style="width: 100%">
                            @foreach ($prestadores as $item)
                                <option value="{{$item->id}}" @if (old('instituicao_prestador_id', $modelo->instituicao_prestador_id) == $item->id)
                                    selected
                                @endif>
                                    {{$item->prestador->nome}} ({{($item->especialidade) ? $item->especialidade->descricao : ""}})
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('instituicao_prestador_id'))
                            <small class="form-control-feedback">{{ $errors->first('instituicao_prestador_id') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $modelo->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <input type="checkbox" name="tipo" id="tipo" class="filled-in chk-col-black" @if ($modelo->tipo == "especial")
                            checked
                        @endif/>
                        <label for="tipo">Receituário de controle especial</label>
                    </div>
                    <div class="col-md-8">
                        <input type="checkbox" name="estrutura" id="estrutura" value='1' class="filled-in chk-col-black" @if ($modelo->estrutura == "livre")
                            checked
                        @endif/>
                        <label for="estrutura">Utilizar receituário livre</label>
                    </div>
                    <div class="livre-form col-md-12 form-group @if($errors->has('texto')) has-danger @endif" style="display: none">
                        <label class="form-control-label p-0 m-0">Texto *</label>
                        <textarea class="form-control summernote @if($errors->has('texto')) form-control-danger @endif" name="texto" id="texto" cols="30" rows="10">
                            @if ($modelo->estrutura == "livre")
                                {{ old('texto', $modelo->receituario['receituario']) }}
                            @else
                                {{ old('texto') }}
                            @endif</textarea>
                        @if($errors->has('texto'))
                            <small class="form-control-feedback">{{ $errors->first('texto') }}</small>
                        @endif
                    </div>
                    <div class="form-medicamentos col-md-12">
                        <div class="col-md-12">
                            <div class="medicamentos_itens row">
                                @if ($modelo->estrutura == "formulario")

                                    @for ($i = 0; $i < count($modelo->receituario); $i++)
                                        <div class="col-md-12 item-medicamento nao-remover">
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
                                                            <option value="{{$item->id}}" @if ($item->id == $modelo->receituario[$i]['medicamento']['medicamento_id'])
                                                                selected
                                                            @endif data-tipo="{{$item->tipo}}">{{$item->nome}} ({{$item->concentracao}} - {{$item->forma_farmaceutica}})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label class="form-control-label">Quantidade *</span></label>
                                                    <input type="text" class="form-control" value="{{$modelo->receituario[$i]['quantidade']}}" name="medicamentos[{{$i}}][quantidade]">
                                                </div>

                                                <div class="col-md-3"></div>
                                                <div class="row col-md-6 adicionar_composicao_campo" @if ($modelo->receituario[$i]['medicamento']['composicao'] == null) style="display: none" @endif>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Composição</h5>
                                                            @if ($modelo->receituario[$i]['medicamento']['composicao'] != null)
                                                                @for ($j = 0; $j < count($modelo->receituario[$i]['medicamento']['composicao']); $j++)
                                                                    <div class="col-md-12 item-composicao-medicamento">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="small" onclick="removerComposicao(this)"  style="color: blue; cursor: pointer;">(remover)</div>
                                                                            </div>
                                                                            <div class="form-group dados_parcela col-md-8">
                                                                                <label class="form-control-label">Substancia *:</label>
                                                                                <input type="text" name="composicoes[{{$i}}][{{$j}}][substancia]" value="{{$modelo->receituario[$i]['medicamento']['composicao'][$j]['substancia']}}" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label class="form-control-label">Concentração *</span></label>
                                                                                <input type="text" class="form-control" name="composicoes[{{$i}}][{{$j}}][concentracao]" placeholder="Ex.: 10 mg/ml, 100 mg, 1000UI" value="{{$modelo->receituario[$i]['medicamento']['composicao'][$j]['concentracao']}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endfor
                                                            @endif
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
                                                    <textarea class="form-control " name="medicamentos[{{$i}}][posologia]" cols="2" rows="2">{{$modelo->receituario[$i]['posologia']}}</textarea>
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
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modeloReceituario.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal inmodal fade bs-example-modal-lg" id="modalMedicamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Cadastro de medicamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="novoMedicamento" method="post" enctype="multipart/form-data">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect text-left salvar_formulario_medicamentos">Salvar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('scripts')
    <script>

    var medicamentosAdicionados = [];
    var quantidade_medicamento = 0;
    var quantidade_composicao_receituario = 0;

        $(document).ready(function() {
            $('.summernote').summernote({
                height: 350,
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                toolbar: [
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['hr']],
                    ['view', ['fullscreen']],
                    ['misc', ['codeview']]
                ],
            });
            quantidade_medicamento = $('.item-medicamento').length;
            quantidade_composicao_receituario = $('.item-composicao-medicamento').length;
            $(".selectfild2Medicamento").select2()
            $("[data-toggle='tooltip']").tooltip()
            carregaComposicao()
            estruturaChange()
        })

        function editarMedicamentos(){
            quantidade_medicamento = $('.item-medicamento').length;
            quantidade_composicao_receituario = 0;
            $(".form-medicamentos").find('.item-medicamento').each(function(index, element){

                if(!$(element).hasClass('nao-remover')){

                    $(element).remove()
                }
            })
        }

        function carregaComposicao(){
            $.ajax({
                url: "{{route('instituicao.modeloReceituario.formAddMedicamentos')}}",
                type: 'get',
                success: function(result) {
                    $("#novoMedicamento").html(result);
                }
            });
        }

        $("#estrutura").on('change', function(){
            estruturaChange()
        })

        function estruturaChange(){
            if($("#estrutura").is(':checked')){
                $(".livre-form").css("display", "block");
                $(".form-medicamentos").css('display', 'none')
            }else{
                $(".livre-form").css('display', 'none')
                $(".form-medicamentos").css("display", "block");
            }
        }

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
                    },
                    complete: () => {

                    }
                });

            }else{
                $(element).parents(".item-medicamento").find(".adicionar_composicao_campo").css('display', 'none');
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

    <script type="text/template" id="item-medicamento">
        <div class="col-md-12 item-medicamento">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-medicamento">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-10">
                    <label class="form-control-label">Medicamento *
                        <span alt="default" class="addMedicamento fas fa-plus-circle" style="cursor: pointer;" onclick="cadastrarMedicamento()">
                            <a class="mytooltip" href="javascript:void(0)" >
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cadastrar medicamento"></i>
                            </a>
                        </span>
                    </label>
                    <select name="medicamentos[#][medicamento]" class="form-control selectfild2Medicamento" style="width: 100%">
                        <option value="">Selecione um medicamento</option>
                        @foreach ($medicamentos as $item)
                            <option value="{{$item->id}}" data-tipo="{{$item->tipo}}">{{$item->nome}} ({{$item->concentracao}} - {{$item->forma_farmaceutica}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Quantidade *</span></label>
                    <input type="text" class="form-control mask_item" name="medicamentos[#][quantidade]">
                </div>
                <div class="col-md-3"></div>
                <div class="row col-md-6 adicionar_composicao_campo" style="display: none">
                    <div class="card">
                        <div class="card-body">
                            <h5>Composição</h5>
                            <div class="composicao_medicamento_itens_receituario row">
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
                </div>
                <div class="form-group col-md-12">
                    <label class="form-control-label">Posologia</span></label>
                    <textarea class="form-control " name="medicamentos[#][posologia]" cols="2" rows="2"></textarea>
                </div>
            </div>
            <hr style="width: 100%">
        </div>
    </script>

    <script type="text/template" id="item-composicao-medicamento-receituario">
        <div class="col-md-12 item-composicao-medicamento">
            <div class="row">
                <div class="col-md-12">
                    <div class="small" onclick="removerComposicao(this)"  style="color: blue; cursor: pointer;">(remover)</div>
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
@endpush
