@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Paciente',
        'breadcrumb' => [
            'Pacientes' => route('instituicao.pessoas.index'),
            'Atualizar',
        ],
    ])
    @endcomponent
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-div, .print-div * {
            visibility: visible;
        }
        .print-div {
            position: absolute;
            widows: 100%;
            left: 0;
            top: 0;

        }
        .no_print {
            display: none !important;
        }
    }
</style>

    <div class="card">
        <div class="card-body ">

            <ul class="nav nav-tabs customtab editarTabs" role="tablist">

                <li class="nav-item dadosPacienteEditar"> <a class="nav-link active show tab-editar-pessoa" data-toggle="tab" href="#editar-pessoa" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Dados Paciente</span></a> </li>

                @can('habilidade_instituicao_sessao', 'visualizar_atendimento_paciente')
                    <li class="nav-item atendimentoPaciente"> <a class="nav-link tab-atendimento-paciente" data-toggle="tab" href="#atendimento-paciente" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Atendimento</span></a> </li>
                @endcan

                
            </ul>

            <div class="tab-content tabcontent-border tabsEditar">
                <div class="tab-pane p-20 active show" id="editar-pessoa" role="tabpanel">
                    <div class="editar-pessoa">
            <form class="print-div" action="{{ route('instituicao.pessoas.update', [$pessoa]) }}" method="post" enctype="multipart/form-data" id="formPessoas">
                @method('put')
                @include('instituicao.pessoas.formularioEditar')
                @can('habilidade_instituicao_sessao', 'cadastrar_carteirinha')
                    <div class="col-sm-12 p-0 m-0">
                        <div class="row no_print">
                            <div class="col-sm-12">
                                <div class="card shadow-none bg-light">
                                    <div class="row d-flex justify-content-between p-2 m-0">
                                        <label class="form-control-label p-0 m-0">Carteirinhas</label>
                                        <button type="button" class="btn btn-success" id="adiciona-carteirinha">+</button>
                                    </div>
                                </div>
                                    
                                            <div class="col-sm-12 p-0 m-0" id="carteirinha-lista">
                                                @if(old('carteirinha'))
                                                    @for ($i = 0; $i < count(old('carteirinha')) ; $i ++)
                                                        <div class="card shadow-none carteirinha-item p-0" id="{{ $i }}">
                                                            <div class="row m-0 p-0">
                                                                <div class="col-sm-12 bg-light border-bottom">
                                                                    <div class="row d-flex justify-content-between p-2 m-0">
                                                                        <label class="form-control-label p-0 m-0">
                                                                            <span class="title"></span>
                                                                        </label>
                                                                        <button type="button" id="" class="btn btn-secondary remover-carteirinha" >x</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="carteirinha[{{$i}}][id]" value="{{old("carteirinha.{$i}.id")}}">
                                                            <input type="hidden" name="carteirinha[{{$i}}][tipo]" class="tipo_carteirinha" value="{{old("carteirinha.{$i}.tipo")}}">
                                                            <div class="row p-2 m-0">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label class="form-control-label p-0 m-0">Convenios <span class="text-danger">*</span></label>
                                                                        <select class="form-control select2" name="carteirinha[{{$i}}][convenio_id]" onchange="changeConvenioPlano(this)" required>
                                                                            <option value='' selected disabled>Selecione o convenio</option>
                                                                            @foreach ($convenios as $item)
                                                                                <option value="{{ $item->id }}" @if (old("carteirinha.{$i}.convenio_id") == $item->id)
                                                                                    selected
                                                                                @endif>{{ $item->nome }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if($errors->has("carteirinha.{$i}.convenio_id"))
                                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.convenio_id") }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                            
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label class="form-control-label p-0 m-0">Planos <span class="text-danger">*</span></label>
                                                                        <select class="form-control select2" name="carteirinha[{{$i}}][plano_id]" id='carteirinha[{{$i}}][plano_id]' required>
                                                                            <option value='' selected disabled>Selecione o plano</option>
                                                                        </select>
                                                                        @if($errors->has("carteirinha.{$i}.plano_id"))
                                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.plano_id") }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <label class="form-control-label p-0 m-0">Carteirinha <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="carteirinha[{{$i}}][carteirinha]" value="{{old("carteirinha.{$i}.carteirinha")}}" placeholder="Carteirinha" required>
                                                                    @if($errors->has("carteirinha.{$i}.carteirinha"))
                                                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.carteirinha") }}</small>
                                                                    @endif
                                                                </div>
                                            
                                                                <div class="col-sm">
                                                                    <label class="form-control-label p-0 m-0">validade <span class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control" name="carteirinha[{{$i}}][validade]" value="{{old("carteirinha.{$i}.validade")}}" placeholder="Validade" required>
                                                                    @if($errors->has("carteirinha.{$i}.validade"))
                                                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.validade") }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @else
                                                    @for ($i = 0; $i < count($pessoa->carteirinha) ; $i ++)
                                                        <div class="card shadow-none carteirinha-item p-0" id="{{ $i }}">
                                                            <div class="row m-0 p-0">
                                                                <div class="col-sm-12 bg-light border-bottom">
                                                                    <div class="row d-flex justify-content-between p-2 m-0">
                                                                        <label class="form-control-label p-0 m-0">
                                                                            <span class="title"></span>
                                                                        </label>
                                                                        <button type="button" id="" class="btn btn-secondary remover-carteirinha" >x</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="carteirinha[{{$i}}][id]" value="{{$pessoa->carteirinha[$i]->id}}">
                                                            <input type="hidden" name="carteirinha[{{$i}}][tipo]" class="tipo_carteirinha" value="existe">
                                                            <div class="row p-2 m-0">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label class="form-control-label p-0 m-0">Convenios <span class="text-danger">*</span></label>
                                                                        <select class="form-control select2" name="carteirinha[{{$i}}][convenio_id]" onchange="changeConvenioPlano(this)" required>
                                                                            <option value='' selected disabled>Selecione o convenio</option>
                                                                            @foreach ($convenios as $item)
                                                                                <option value="{{ $item->id }}" @if (old("carteirinha.{$i}.convenio_id", $pessoa->carteirinha[$i]->convenio_id) == $item->id)
                                                                                    selected
                                                                                @endif>{{ $item->nome }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if($errors->has("carteirinha.{$i}.convenio_id"))
                                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.convenio_id") }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                            
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label class="form-control-label p-0 m-0">Planos <span class="text-danger">*</span></label>
                                                                        <select class="form-control select2" name="carteirinha[{{$i}}][plano_id]" id='carteirinha[{{$i}}][plano_id]' required>
                                                                            <option value='{{$pessoa->carteirinha[$i]->plano_id}}' selected>{{$pessoa->carteirinha[$i]->plano[0]->nome}}</option>
                                                                        </select>
                                                                        @if($errors->has("carteirinha.{$i}.plano_id"))
                                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.plano_id") }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <label class="form-control-label p-0 m-0">Carteirinha <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="carteirinha[{{$i}}][carteirinha]" value="{{old("carteirinha.{$i}.carteirinha",$pessoa->carteirinha[$i]->carteirinha)}}" placeholder="Carteirinha" required>
                                                                    @if($errors->has("carteirinha.{$i}.carteirinha"))
                                                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.carteirinha") }}</small>
                                                                    @endif
                                                                </div>
                                            
                                                                <div class="col-sm">
                                                                    <label class="form-control-label p-0 m-0">validade <span class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control" name="carteirinha[{{$i}}][validade]" value="{{old("carteirinha.{$i}.validade", $pessoa->carteirinha[$i]->validade)}}" placeholder="Validade" required>
                                                                    @if($errors->has("carteirinha.{$i}.validade"))
                                                                        <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.validade") }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @endif
                                                <div class="add-class-carteirinha"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right pb-2 no_print">
                                    <button type="button" class="btn btn-secundary btn-circle" onclick="imprimir()" data-toggle="tooltip" data-original-title="Imprimir" >
                                        <i class="mdi mdi-printer"></i>
                                    </button>
                                    <button type="submit" id="salvar2" class="btn btn-success waves-effect waves-light m-r-10">
                                        <i class="mdi mdi-check"></i> Salvar
                                    </button>
                                </div>
                            @endcan
                        </form>
                        
                    </div>
                </div>

                <div class="tab-pane p-20 no_print" id="atendimento-paciente" role="tabpanel">
                    <div class="atendimento-paciente">
                        
                    </div>
                </div>
            
               
            </div>
            
            <a class="no_print" href="{{ route('instituicao.pessoas.index') }}" style="float: right">
                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
            </a>
        </div>
    </div>
@endsection

@push('scripts')

    <script type="text/template" id="pessoa-nao-associada">
        <small class="form-text text-primary pessoa-nao-registrada pessoa-alerta">
            <i class="ti-check"></i> Disponível
        </small>
    </script>

    <script type="text/template" id="pessoa-associada">
        <small class="form-text text-danger pessoa-registrada pessoa-alerta">
            <i class="ti-close"></i> Proibido
        </small>
    </script>

    <script type="text/template" id="base-carteirinha-item">
        <div class="card shadow-none carteirinha-item p-0">
            <div class="row m-0 p-0">
                <div class="col-sm-12 bg-light border-bottom">
                    <div class="row d-flex justify-content-between p-2 m-0">
                        <label class="form-control-label p-0 m-0">
                            <span class="title"></span>
                        </label>
                        <button type="button" id="" class="btn btn-secondary remover-carteirinha" >x</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="carteirinha[#][id]" value="">
            <input type="hidden" name="carteirinha[#][tipo]" class="tipo_carteirinha" value="novo">
            <div class="row p-2 m-0">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Convenios <span class="text-danger">*</span></label>
                        <select class="form-control select2new" name="carteirinha[#][convenio_id]" onchange="changeConvenioPlano(this)" required>
                            <option value='' selected disabled>Selecione o convenio</option>
                            @foreach ($convenios as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Planos <span class="text-danger">*</span></label>
                        <select class="form-control select2new" name="carteirinha[#][plano_id]" required>
                            <option value='' selected disabled>Selecione o plano</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm">
                    <label class="form-control-label p-0 m-0">Carteirinha <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="carteirinha[#][carteirinha]" placeholder="Carteirinha" required>
                </div>

                <div class="col-sm">
                    <label class="form-control-label p-0 m-0">validade <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="carteirinha[#][validade]" placeholder="Validade" required>
                </div>
            </div>
        </div>
    </script>

    <script>
        var quantidade_carteirinha = 0;
        var pessoa_id = "{{$pessoa->id}}"
        $(document).ready(function(){
            quantidade_carteirinha = $(".carteirinha-item").length
            getPlanos()
            function blockButtons() {
                $('#salvar').prop('disabled', true);
                $('#salvar2').prop('disabled', true);
                $('#adiciona-documento').prop('disabled', true);
            }

            function desblockButtons() {
                $('#salvar').prop('disabled', false);
                $('#salvar2').prop('disabled', false);
                $('#adiciona-documento').prop('disabled', false);
            }

            function requestPessoa(doc) {
                $(`input[name="${doc}"]`).on('change',function (e) {
                    if($(this).data('prev')!=$(this).val()) {
                        if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                            $.ajax({
                                url: '{{ route("instituicao.pessoas.getPessoa") }}',
                                method: 'POST', dataType: 'json',
                                data: { valor: $(this).val(), documento: doc, '_token': '{{ csrf_token() }}' },
                                success: function (response) {
                                    if (response.status==0) {
                                        /* Se a pessoa já estiver associada à esta instituição */
                                        $(`.${doc}-campo .pessoa-alerta`).remove();
                                        $(`.${doc}-campo`).append($($('#pessoa-associada').html()));
                                        blockButtons()
                                    }
                                    if (response.status==1) {
                                        /* Se a pessoa não estiver associada à esta instituição */
                                        $(`.${doc}-campo .pessoa-alerta`).remove();
                                        $(`.${doc}-campo`).append($($('#pessoa-nao-associada').html()));
                                        desblockButtons()
                                    }
                                }
                            })
                        }
                    } else {
                        $(`.${doc}-campo .pessoa-alerta`).remove();
                    }
                });
            }

            function personalidade() {
                let personalidade = $('select[name="personalidade"]').val();
                if(personalidade) {
                    $('#campos-fisico-juridico').show();
                    if(personalidade == 1) {
                        $('#campos-pessoa-juridica').hide();
                        $('#personalidade-selecionada').text('Pessoa Física');
                        $('#campos-pessoa-fisica').show();
                    }
                    if(personalidade == 2) {
                        $('#personalidade-selecionada').text('Pessoa Jurídica');
                        $('#campos-pessoa-fisica').show();
                        $('#campos-pessoa-juridica').show();
                    }
                }
            }

            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });

            $('select[name="personalidade"]').on('change', function(){
                personalidade();
            });

            personalidade();
            requestPessoa('cpf');
            requestPessoa('cnpj');


            $(".select2-simples").each(function(){
                $(this).select2({
                    tags: true
                });
            });
            
            $('#formPessoas').on('submit', function(e){
                e.preventDefault()
                var formData = new FormData($(this)[0]);
                $.ajax("{{route('instituicao.pessoas.update', [$pessoa])}}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function (response) {
                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });
                        if(response.icon=="success"){
                            window.location="{{ route('instituicao.pessoas.edit', [$pessoa]) }}";
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') ;
                    },
                    error: function (response) {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: key+": "+response.responseJSON.errors[key][0],
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

        });

        function imprimir(){
            // var conteudo = document.getElementById('formPessoas').innerHTML,
            // tela_impressao = window.open('about:blank');

            // tela_impressao.document.write(conteudo);
            // tela_impressao.window.print();
            // tela_impressao.window.close();

            window.print();
        }

        $("#adiciona-carteirinha").on('click', function(){
            $($('#base-carteirinha-item').html()).insertBefore(".add-class-carteirinha");

            $('.select2new').select2();
            $('.select2new').removeClass('select2new');

            $("[name^='carteirinha[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_carteirinha));
            })

            quantidade_carteirinha++;
        })

        function changeConvenioPlano(element){
            var id = $('option:selected', element).val();
            var posicao = $(element).attr('name').split("").filter(n => (Number(n) || n == 0)).join("")
            getPlano(id, posicao, false)
        }

        function getPlanos(){
            for (let index = 0; index < quantidade_carteirinha; index++) {
                var id = $("[name^='carteirinha["+index+"][convenio_id]'").val()
                getPlano(id, index, true)
            }
        }

        function getPlano(id, posicao, tipo){
            if(id != ''){
                $.ajax({
                    url: "{{route('instituicao.carteirinhas.getPlanos', ['convenio_id' => 'Id'])}}".replace('Id', id),
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(retorno){
                        if(tipo == false){
                            $("[name^='carteirinha["+posicao+"][plano_id]'").find('option').filter(':not([value=""])').remove();
                        }

                        for (i = 0; i < retorno.length; i++) {
                            $("[name^='carteirinha["+posicao+"][plano_id]'").append('<option value="'+ retorno[i].id +'">' + retorno[i].nome + '</option>');
                        }
                    }
                })
           }
        }

        $("#carteirinha-lista").on('click', '.remover-carteirinha', function(e){
            e.preventDefault()
            var tipo = $(this).parents(".carteirinha-item").find(".tipo_carteirinha").val();
            
            if(tipo == "novo"){
                $(this).parents('.carteirinha-item').remove();
            }else if(tipo == "existe"){
                
                $(this).parents(".carteirinha-item").find(".tipo_carteirinha").val("excluir");
                $(this).parents(".carteirinha-item").css('border-color', 'red');
            }else{
                
                $(this).parents(".carteirinha-item").find(".tipo_carteirinha").val("existe");
                $(this).parents(".carteirinha-item").css('border-color', '#d7dfe3');
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        ///////// SEÇÃO ATENDIMENTOS ///////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        function atendimentoAtualizar(result)
        {
            $('.atendimento-paciente').html(result)            
            window.livewire.rescan()
            $('.button_tooltip').tooltip()

            $(".selectfild2").each(function () {
                var $select = $(this);
                if (!$(this).attr('wire:model')) {
                    $select.select2();
                    return;
                }

                var $id = $(this).parents('[wire\\:id]').attr('wire:id');
                $select.select2().on('select2:select', function (e) {
                    window.livewire.find($id).set($(this).attr('wire:model'), e.params.data.id);
                });
            });
            
        }

        $('.tab-atendimento-paciente').on('click', function(){
        
            if($('.atendimento-paciente').hasClass('carregado')){
                return
            }else{

                $('.atendimento-paciente').addClass('carregado')

                $.ajax({
                    url: "{{route('instituicao.atendimentos_paciente.index', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', pessoa_id),
                    type: 'GET',
                    beforeSend: () => {
                        $('.atendimento-paciente').addClass('loader')
                        $('.loading').css('display', 'block');
                    },

                    success: function(result) {
                        
                        atendimentoAtualizar(result);
                        $('.loading').css('display', 'none');
                        
                    },

                    complete: () => {
                        $('.atendimento-paciente').removeClass('loader') 
                    }

                });
            }
        })

        $(".atendimento-paciente").on('click', '.nova_atendimento', function(){
            $(".novo_atendimento").css('display', 'block')
            $(".lista_atendimento").css('display', 'none')
            callCriarAtendimento();
        })

        $(".atendimento-paciente").on('click', '.cancelar_atendimento', function(){
            $(".novo_atendimento").css('display', 'none')
            $(".lista_atendimento").css('display', 'block')
        })

        function callCriarAtendimento(){
            $.ajax({
                url: "{{route('instituicao.atendimentos_paciente.create', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', pessoa_id),
                type: 'GET',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                    $(".novo_atendimento").html('')
                },

                success: function(result) {
                    
                    $(".novo_atendimento").html(result)
                    
                },

                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }

            });
        }

        $(".atendimento-paciente").on('submit', "#form_atendimento_criar", function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);
            
            $.ajax("{{route('instituicao.atendimentos_paciente.store', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', pessoa_id), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $(".atendimento-paciente").html('')
                    $(".atendimento-paciente").addClass('loader')
                    $('.loading').css('display', 'block');
                },
                success: function (result) {
                    atendimentoAtualizar(result);
                    $('.loading').css('display', 'none');
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Atendimento cadastrado com sucesso!',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    })
                },
                complete: () => {
                    $(".atendimento-paciente").removeClass('loader')
                }
            })
        })
        
        $(".atendimento-paciente").on('submit', "#form_atendimento_editar", function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);
            var id = $("#id_atendimento_paciente").val()
            
            $.ajax("{{route('instituicao.atendimentos_paciente.update', ['pessoa' => 'pessoa_id', 'atendimento_paciente' => 'atendimento_paciente_id'])}}".replace('pessoa_id', pessoa_id).replace('atendimento_paciente_id', id), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $(".atendimento-paciente").html('')
                    $(".atendimento-paciente").addClass('loader')
                    $('.loading').css('display', 'block');
                },
                success: function (result) {
                    atendimentoAtualizar(result);
                    $('.loading').css('display', 'none');
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Atendimento editado com sucesso!',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    })
                },
                complete: () => {
                    $(".atendimento-paciente").removeClass('loader')
                }
            })
        })

    </script>
@endpush



