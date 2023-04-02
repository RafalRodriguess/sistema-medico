@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Conta a Receber',
        'breadcrumb' => [
            'Contas a Receber' => route('instituicao.contasReceber.index'),
            'Nova',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.contasReceber.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('conta_id')) has-danger @endif">
                        <label class="form-control-label">Conta caixa: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esta é a conta onde será debitada o dinheiro, se for pagamento na empresa por dinheiro 'Caixa' e na conta no banco utilizando cheque ou pagamento online 'Conta Banco XX..."></i>
                        </label>
                        <select name="conta_id" class="form-control selectfild2 @if($errors->has('conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione uma conta</option>
                            @foreach ($contas as $item)
                                <option value="{{$item->id}}" @if (old('conta_id') == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 @if($errors->has('plano_conta_id')) has-danger @endif">
                        <label class="form-control-label">Plano de conta: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Este é o filtro por tipo de pagamento, que deve ser escolhido o plano exato que se associa ao pagamento"></i>
                        </label>
                        <select name="plano_conta_id" class="form-control selectfild2 @if($errors->has('plano_conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um plano de conta</option>
                            @foreach ($planosConta as $item)
                                <option value="{{$item->id}}" @if (old('plano_conta_id') == $item->id)
                                    selected="selected"
                                @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('plano_conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deve ser escolhido o cliente do serviço ou venda, caso não seja cadastrado, cadastrar o mesmo antes de inserir a conta"></i>
                        </label>
                        <select name="tipo" class="form-control selectfild2 @if($errors->has('tipo')) form-control-danger @endif" style="width: 100%" id="tipo">
                            @foreach ($tipos as $item)
                                <option value="{{$item}}" @if (old('tipo') == $item)
                                    selected="selected"
                                @endif>{{App\ContaReceber::tipos_texto_all($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 paciente @if($errors->has('pessoa_id')) has-danger @endif" style="display: block">
                        <label class="form-control-label">
                            Pacientes: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deixar campo vazio para gerar contas a receber avulsa"></i>
                        </label>
                        <select name="pessoa_id" class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="pessoa_id">
                            <option value="">Paciente avulso</option>
                            {{-- @if ($pacientes)
                                @foreach ($pacientes as $item)
                                    <option value="{{$item->id}}" @if (old('pessoa_id') == $item->id)
                                        selected="selected"
                                    @endif>{{$item->nome}}</option>
                                @endforeach
                            @endif --}}
                        </select>
                        @if($errors->has('pessoa_id'))
                            <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 convenio @if($errors->has('convenio_id')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Convenios: *</span></label>
                        <select name="convenio_id" class="form-control selectfild2 @if($errors->has('convenio_id')) form-control-danger @endif" style="width: 100%" id="convenio_id">
                            <option value="">Selecione um convenio</option>
                            @if ($convenios)
                                @foreach ($convenios as $item)
                                    <option value="{{$item->id}}" @if (old('convenio_id') == $item->id)
                                        selected="selected"
                                    @endif>{{$item->nome}}</option>
                                @endforeach
                            @endif
                        </select>
                        @if($errors->has('convenio_id'))
                            <div class="form-control-feedback">{{ $errors->first('convenio_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('data_compensacao')) has-danger @endif">
                        <label class="form-control-label">Data compensação:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que a compra ou despesa ocorreu"></i>
                        </label>
                        <input type="date" name="data_compensacao" class="form-control @if ($errors->has('data_compensacao')) form-control-danger @endif" value="{{old('data_compensacao')}}">
                        @if ($errors->has('data_compensacao'))
                            <div class="form-control-feedback">{{$errors->first('data_compensacao')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-5">
                        <label class="form-control-label">Descrição:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso queira detalhar melhor o seu pagamento"></i>
                        </label>
                        <input type="text" name="descricao" class="form-control" value="{{old('descricao')}}">
                    </div>

                    <div class="form-group col-md-3">
                        <label class="form-control-label">Nº documento:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso tenha número do boleto, número da nota, etc..."></i>
                        </label>
                        <input type="text" name="num_documento" class="form-control" value="{{old('num_documento')}}">
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('data_vencimento')) has-danger @endif">
                        <label class="form-control-label">Data vencimento: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="date" name="data_vencimento" id="data_vencimento" class="form-control @if ($errors->has('data_vencimento')) form-control-danger @endif" value="{{old('data_vencimento', date('Y-m-d'))}}">
                        @if ($errors->has('data_vencimento'))
                            <div class="form-control-feedback">{{$errors->first('data_vencimento')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('valor_parcela')) has-danger @endif">
                        <label class="form-control-label">Valor: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="text" alt="decimal" name="valor_parcela" id="valor_parcela" class="form-control @if ($errors->has('valor_parcela')) form-control-danger @endif" value="{{old('valor_parcela')}}">
                        @if ($errors->has('valor_parcela'))
                            <div class="form-control-feedback">{{$errors->first('valor_parcela')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('forma_pagamento')) has-danger @endif">
                        <label class="form-control-label">Forma de pagamento: *</label>
                        <select class="form-control select_parcela selectfild2 @if($errors->has('forma_pagamento')) form-control-danger @endif" name="forma_pagamento" id="forma_pagamento" style="width: 100%">
                            @foreach ($formas_pagamento as $forma_pagamento)
                                <option value="{{$forma_pagamento}}"@if (old('forma_pagamento') == $forma_pagamento)
                                    selected="selected"
                                @endif>{{ App\ContaReceber::forma_pagamento_texto($forma_pagamento)}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('forma_pagamento'))
                            <div class="form-control-feedback">{{$errors->first('forma_pagamento')}}</div>
                        @endif
                    </div>

                    {{-- <div class="form-group col-md-3 @if($errors->has('forma_recebimento_id')) has-danger @endif">
                        <label class="form-control-label">Forma de recebimento: *</label>
                        <select class="form-control select_parcela selectfild2 @if($errors->has('forma_recebimento_id')) form-control-danger @endif" name="forma_recebimento_id" id="forma_recebimento_id" style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach ($formasRecebimento as $item)
                                <option value="{{$item->id}}"@if (old('forma_recebimento_id') == $item->id)
                                    selected="selected"
                                @endif>{{ $item->forma_recebimento }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('forma_recebimento_id'))
                            <div class="form-control-feedback">{{$errors->first('forma_recebimento_id')}}</div>
                        @endif
                    </div> --}}

                    <div class="cheque_data col-md-12" style="display: none">
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('titular')) has-danger @endif">
                                <label class="form-control-label">Titular</label>
                                <input class="form-control" type="text" name="titular" id="titular" value="{{old('titular')}}">
                                @if($errors->has('titular'))
                                    <div class="form-control-feedback">{{ $errors->first('titular') }}</div>
                                @endif
                            </div>

                            <div class="col-md-3 form-group @if($errors->has('banco')) has-danger @endif">
                                <label for="form-control-label">Banco</label>
                                <input class="form-control" type="text" name="banco" id="banco" value="{{old('banco')}}">
                                @if ($errors->has('banco'))
                                    <div class="form-control-feedback">{{ $errors->first('banco')}}</div>
                                @endif
                            </div>

                            <div class="col-md-3 form-group @if($errors->has('numero_cheque')) has-danger @endif">
                                <label for="form-control-label">Número do cheque</label>
                                <input class="form-control" type="text" name="numero_cheque" id="numero_cheque" value="{{old('numero_cheque')}}">
                                @if ($errors->has('numero_cheque'))
                                    <div class="form-control-feedback">{{ $errors->first('numero_cheque')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 tipo_parcelamento">
                        <label class="form-control-label">Tipo de parcelamento: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Selecione o período de cobrança"></i>
                        </label>
                        <select class="form-control" name="tipo_parcelamento" id="tipo_parcelamento">
                            <option value="">Parcela única</option>
                            <option value="diario" @if (old('tipo_parcelamento') == 'diario') selected="selected" @endif>Diário</option>
                            <option value="semanal" @if (old('tipo_parcelamento') == 'semanal') selected="selected" @endif>Semanal</option>
                            <option value="quinzenal" @if (old('tipo_parcelamento') == 'quinzenal') selected="selected" @endif>Quinzenal</option>
                            <option value="mensal" @if (old('tipo_parcelamento') == 'mensal') selected="selected" @endif>Mensal</option>
                            <option value="anual" @if (old('tipo_parcelamento') == 'anual') selected="selected" @endif>Anual</option>
                        </select>
                    </div>

                    <div class="form-group col-md-5 num_parcelas @if($errors->has('num_parcelas')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Nº de parcelas: *</label>
                        <input type="number" name="num_parcelas" class="form-control @if ($errors->has('num_parcelas')) form-control-danger @endif" value="{{old('num_parcelas', 2)}}" id="num_parcelas">
                        @if ($errors->has('num_parcelas'))
                            <div class="form-control-feedback">{{$errors->first('num_parcelas')}}</div>
                        @endif
                    </div>

                    <div class="col-md-2 gerar_parcelas" style="display: none">
                        <button type="button" class="btn btn-danger waves-effect waves-light m-r-10" style="margin-top: 31px; width: 100%" onclick="gerarParcelas()">Gerar parcelas</button>
                    </div>

                    <div class="col-md-12">
                        <div class="parcelas_extras row"></div>
                    </div>

                    <div class="form-group col-md-12 @if($errors->has('obs')) has-danger @endif">
                        <label class="form-control-label">Observação: </label>
                        <textarea name="obs" id="obs" class="form-control" cols="5" rows="4">{{old('obs')}}</textarea>
                    </div>

                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.contasReceber.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var quantidade = 0

        $(document).ready(function(){
            tipoValue();
            tipoParcelamento();
            formaPagamentoCheque();

            $("#pessoa_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando paciente (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.contasPagar.getPacientes')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });
        })

        function formaPagamentoCheque(){
            if($("#forma_pagamento").val() == 'cheque'){
                $(".cheque_data").css('display', 'block');
                $("#data_compensacao").prop('disabled', false);
            }else{
                $(".cheque_data").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
            }
        }

        $("#forma_pagamento").on('change', function() {
            formaPagamentoCheque()
        })

        function tipoValue(){
            console.log($("#tipo").val());

            if($("#tipo").val() == 'paciente'){
                $(".paciente").css('display', 'block');
                $(".convenio").css('display', 'none');
                $("#pessoa_id").val('').change();
                $("#convenio_id").val('').change();
            }else{
                $(".paciente").css('display', 'none');
                $(".convenio").css('display', 'block');
                $("#pessoa_id").val('').change();
                $("#convenio_id").val('').change();
            }
        }

        $("#tipo").on('change', function(){
            tipoValue();
        })

        function tipoParcelamento(){
            if($("#tipo_parcelamento").val() == ''){
                $(".tipo_parcelamento").removeClass('col-md-5');
                $(".tipo_parcelamento").addClass('col-md-12');
                $(".num_parcelas").css('display', 'none');
                $(".gerar_parcelas").css('display', 'none');
            }else{
                $(".tipo_parcelamento").removeClass('col-md-12');
                $(".tipo_parcelamento").addClass('col-md-5');
                $(".num_parcelas").css('display', 'block');
                $("#num_parcelas").val(2);
                $(".gerar_parcelas").css('display', 'block');
            }
        }

        $("#tipo_parcelamento").on('change', function(){
            tipoParcelamento();
        })

        function gerarParcelas(){
            quantidade = 0;

            tipo_parcelamento = $("#tipo_parcelamento").val()
            num_parcelas = $("#num_parcelas").val();
            data_vencimento = $("#data_vencimento").val()
            valor_parcelas = $("#valor_parcela").val()

            data_vencimento = data_vencimento.split('-');

            data = new Date(data_vencimento[0], data_vencimento[1] - 1, data_vencimento[2])
            // data = acrescimoData(tipo_parcelamento, data);
            data = ( data.getFullYear() + "-" + (adicionaZero(data.getMonth()+1).toString()) + "-" + adicionaZero(data.getDate().toString()));

            html = "";

            for (let index = 2; index <= num_parcelas; index++) {
                data = data.split('-');

                data = new Date(data[0], data[1] - 1, data[2])
                data = acrescimoData(tipo_parcelamento, data);
                data = ( data.getFullYear() + "-" + (adicionaZero(data.getMonth()+1).toString()) + "-" + adicionaZero(data.getDate().toString()));

                quantidade ++;

                html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".data_vencimento')) has-danger @endif'>"
                    +"<label class='form-control-label'>"+index+"º) parcela: *</label>"
                    +"<input type='date' name='parcelas["+quantidade+"][data_vencimento]' class='form-control @if ($errors->has('parcelas."+quantidade+".data_vencimento')) form-control-danger @endif' value='"+data+"'>"
                    +"@if ($errors->has('parcelas."+quantidade+".data_vencimento'))"
                        +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".data_vencimento')}}</div>"
                    +"@endif"
                +"</div>"

                html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".valor')) has-danger @endif'>"
                    +"<label class='form-control-label'>Valor: *</label>"
                    +"<input type='text' alt='decimal' name='parcelas["+quantidade+"][valor]' class='form-control set-mask @if ($errors->has('parcelas."+quantidade+".valor')) form-control-danger @endif' value='"+valor_parcelas+"'>"
                    +"@if ($errors->has('parcelas."+quantidade+".valor'))"
                        +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".valor')}}</div>"
                    +"@endif"
                +"</div>"
            }


            $(".parcelas_extras").html(html)

            $(".set-mask").setMask();
            $(".set-mask").removeClass('set-mask');
        }

        function acrescimoData(tipo_parcelamento, data){
            if(tipo_parcelamento == 'diario'){
                acrescimo_data = 1;
            }
            if(tipo_parcelamento == 'semanal'){
                acrescimo_data = 7;
            }
            if(tipo_parcelamento == 'quinzenal'){
                acrescimo_data = 15;
            }
            if(tipo_parcelamento == 'mensal'){
                acrescimo_data = 1;
                data.setMonth(data.getMonth() + acrescimo_data)
                return data
            }
            if(tipo_parcelamento == 'anual'){
                acrescimo_data = 1;
                data.setYear(data.getFullYear() + acrescimo_data)
                return data
            }

            data.setDate(data.getDate() + acrescimo_data)
            return data
        }

        function adicionaZero(numero){
            if (numero <= 9)
                return "0" + numero;
            else
                return numero;
        }
    </script>
@endpush
