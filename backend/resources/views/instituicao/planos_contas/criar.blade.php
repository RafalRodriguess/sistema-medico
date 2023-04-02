@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Plano de Conta',
        'breadcrumb' => [
            'Plano de Conta' => route('instituicao.planosContas.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">

            <form action="{{ route('instituicao.planosContas.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-9 form-group @if($errors->has('plano_conta_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Plano de contas Pai</label>
                        <select class="form-control p-0 m-0 select2Live" name="plano_conta_id" id="pai" >
                            <option value="" selected>Nenhum</option>
                            @foreach ($planosContas as $planoConta)
                                <option value="{{ $planoConta->id }}">{{ $planoConta->codigo }} - {{ $planoConta->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('plano_conta_id'))
                            <small class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('codigo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Código de Hierarquia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control p-0 m-0" value="{{ $totalContaspai }}" name="codigo" id="codigo" readonly>
                        @if($errors->has('codigo'))
                            <small class="form-control-feedback">{{ $errors->first('codigo') }}</small>
                        @endif
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control p-0 m-0" value="{{ old('descricao') }}"
                            name="descricao" id="descricao" >
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('padrao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Padrão <span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('padrao')) form-control-danger @endif" name="padrao" id="padrao">
                            <option value="1">Débito</option>
                            <option value="0">Crédito</option>
                        </select>
                        @if($errors->has('padrao'))
                            <small class="form-control-feedback">{{ $errors->first('padrao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('rateio_auto')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rateio automático <span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('rateio_auto')) form-control-danger @endif" name="rateio_auto" id="rateio_auto">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                        @if($errors->has('rateio_auto'))
                            <small class="form-control-feedback">{{ $errors->first('rateio_auto') }}</small>
                        @endif
                    </div>
                </div>

                @if($errors->has('porcentagem'))
                    <div id="erro-porcentagem" class="alert alert-danger p-2 col-md-6 col-sm-8">{{ $errors->first('porcentagem') }}</div>
                @endif
                <div class='rateio'>
                    <div class="row">
                        @include('instituicao.planos_contas.centro_custo_criar')

                        <div class="form-group col-md-12 add-class" >
                            <span alt="default" class="add-cc fas fa-plus-circle">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar centro de custo"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.planosContas.index') }}">
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
        var quantidade_cc = 0;
        const erro = {{ $errors->has('porcentagem') }}

        $(document).ready(function(){
            tipo()
            getCod()
            if(erro) {
                setTimeout(function(){
                    $('#erro-porcentagem').hide()
                }, 3000)
            }
        })

        $('#pai').on('change', function(){
            getCod()
        })

        $('#rateio_auto').on('change', function(){
            tipo()
        })

        function tipo(){
            let tipo = $('#rateio_auto option:selected').val()

            if(tipo == 1){
                $('.rateio').css('display','block')
            }else{
                $('.rateio').css('display','none')
            }
        }

        function getCod(){
            if($('#pai').val() == ''){
                $('#codigo').val('{{ $totalContaspai }}')
            }else{
                id = $('#pai').val();

                $.ajax({
                    url: "{{route('instituicao.planosContas.getCodigoPai', ['planoConta' => 'plano_conta_id'])}}".replace('plano_conta_id', id),
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(retorno){
                        console.log(retorno);
                        $('#codigo').val(retorno['codPai']+'.'+retorno['totalFilhos']);
                        $('#padrao').val(retorno['padrao']);
                        $('#padrao').attr('readonly');
                    }
                })

            }

        }

        function retornaFormatoValor(valor){
            var novo = valor;
            novo = novo.replace('.','')
            novo = novo.replace(',','.')
            return novo;
        }
    </script>
@endpush
