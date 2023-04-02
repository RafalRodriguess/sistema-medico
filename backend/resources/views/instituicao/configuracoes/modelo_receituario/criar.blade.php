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
        'titulo' => 'Cadastrar Modelo de receituário',
        'breadcrumb' => [
            'Modelo de receituário' => route('instituicao.modeloReceituario.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modeloReceituario.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('instituicao_prestador_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prestador</label>
                        <select class="form-control select2 @if($errors->has('instituicao_prestador_id')) form-control-danger @endif" name="instituicao_prestador_id" id="instituicao_prestador_id" style="width: 100%">
                            @foreach ($prestadores as $item)
                                <option value="{{$item->id}}" @if (old('instituicao_prestador_id') == $item->id)
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
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <input type="checkbox" name="tipo" id="tipo" class="filled-in chk-col-black"/>
                        <label for="tipo">Receituário de controle especial</label>
                    </div>
                    <div class="col-md-8">
                        <input type="checkbox" name="estrutura" id="estrutura" value='1' class="filled-in chk-col-black"/>
                        <label for="estrutura">Utilizar receituário livre</label>
                    </div>
                    <div class="livre-form col-md-12 form-group @if($errors->has('texto')) has-danger @endif" style="display: none">
                        <label class="form-control-label p-0 m-0">Texto *</label>
                        <textarea class="form-control summernote @if($errors->has('texto')) form-control-danger @endif" name="texto" id="texto" cols="30" rows="10">
                            {{ old('texto') }}</textarea>
                        @if($errors->has('texto'))
                            <small class="form-control-feedback">{{ $errors->first('texto') }}</small>
                        @endif
                    </div>
                    <div class="form-medicamentos col-md-12">

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
            carregaFormMedicamento()
            carregaComposicao()
        })

        function carregaFormMedicamento(){
            $.ajax({
                url: "{{route('instituicao.modeloReceituario.formMedicamentos')}}",
                type: 'get',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    $(".form-medicamentos").html(result);
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });
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
            if($("#estrutura").is(':checked')){
                $(".livre-form").css("display", "block");
                $(".form-medicamentos").css('display', 'none')
            }else{
                $(".livre-form").css('display', 'none')
                $(".form-medicamentos").css("display", "block");
            }
        })
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
