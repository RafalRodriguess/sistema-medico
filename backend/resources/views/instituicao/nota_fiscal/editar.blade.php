@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Nota fiscal',
        'breadcrumb' => [
            'Nota Fiscal' => route('instituicao.notasFiscais.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.notasFiscais.update', [$nota]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cod_servico_municipal')) has-danger @endif">
                        <label class="form-control-label">Cod Serviço</label>
                        <input type="text" class="form-control"name="cod_servico_municipal" value="{{ old('cod_servico_municipal', $nota->cod_servico_municipal) }}" readonly>
                        @if($errors->has('cod_servico_municipal'))
                            <small class="form-control-feedback">{{ $errors->first('cod_servico_municipal') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('aliquota_iss')) has-danger @endif">
                        <label class="form-control-label">Aliquota Iis</label>
                        <input type="text" class="form-control" name="aliquota_iss" value="{{ old('aliquota_iss', $nota->aliquota_iss) }}" id="aliquota_iss" readonly alt="decimal">
                        @if($errors->has('aliquota_iss'))
                            <small class="form-control-feedback">{{ $errors->first('aliquota_iss') }}</small>
                        @endif
                    </div>
                    
                    <div class="col-md form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição</label>
                        <input type="text" class="form-control"
                            name="descricao" value="{{ old('descricao', $nota->descricao) }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('pessoa_id')) has-danger @endif">
                        <label class="form-control-label">Paciente</label>
                        <input type="hidden" value="{{$nota->paciente->id}}" name="pessoa_id" id="pessoa_id">
                        <input type="text" class="form-control" value="#{{$nota->paciente->id}} {{$nota->paciente->nome}} @if(!empty($nota->paciente->cpf)) ({{$nota->paciente->cpf}}) @endif" readonly id="pessoa">
                        @if($errors->has('pessoa_id'))
                            <small class="form-control-feedback">{{ $errors->first('pessoa_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('contas_receber_id')) has-danger @endif">
                        <label class="form-control-label">Contas receber</label>
                        <hr>
                        <div class="col-sm-12" id="contasReceberSelec">
                            @foreach($contas_receber as $item)
                                <div class='row'>
                                    <div class='form-group col-sm-9'>
                                        <label>Descrição</label>
                                        <input type='hidden' name='contas_receber[]' value='{{$item->id}}'>
                                        <input type='text' class='form-control' readonly value='#{{$item->id}} {{$item->descricao}}'>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <label>Valor</label>
                                        <input type='text' alt='decimal' class='form-control valor_parcela' readonly value='{{$item->valor_parcela}}'>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('valor_total')) has-danger @endif">
                        <label class="form-control-label">Valor total</label>
                        <input type="text" name="valor_total" class="form-control" value="{{old('valor_total', $nota->valor_total)}}" id="valorTotalnota" alt="decimal">
                        @if($errors->has('valor_total'))
                            <small class="form-control-feedback">{{ $errors->first('valor_total') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="form-control-label">Valor IIS</label>
                        <input type="text" name="valor_iis" class="form-control" value="{{old('valor_iis', $nota->valor_iis)}}" id="valorIis" alt="decimal" readonly>
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="form-control-label">ISS retirdo na fonte</label>
                        <select class="form-control" name="iss_retido_fonte" id="iss_retido_fonte" readonly disabled>
                            <option value="0" @if($nota->iss_retido_fonte == 0) selected @endif>Não</option>
                            <option value="1" @if($nota->iss_retido_fonte == 1) selected @endif>Sim</option>
                        </select>
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('deducoes')) has-danger @endif">
                        <label class="form-control-label">Deduções</label>
                        <input type="text" name="deducoes" class="form-control" value="{{old('deducoes', $nota->deducoes)}}"  id="deducoes" alt="decimal">
                        @if($errors->has('deducoes'))
                            <small class="form-control-feedback">{{ $errors->first('deducoes') }}</small>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="card card-body">
                    <h4>Endereço tomador</h4>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Estado</label>
                            <input type="text" name="cliente_uf" class="form-control" value="{{$nota->cliente_uf}}" id="cliente_uf" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Cidade</label>
                            <input type="text" name="cliente_cidade" class="form-control" value="{{$nota->cliente_cidade}}" id="cliente_cidade" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Bairro</label>
                            <input type="text" name="cliente_bairro" class="form-control" value="{{$nota->cliente_bairro}}" id="cliente_bairro" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Logradouro</label>
                            <input type="text" name="cliente_logradouro" class="form-control" value="{{$nota->cliente_logradouro}}" id="cliente_logradouro" readonly>
                        </div>

                        <div class="col-md-2 form-group">
                            <label class="form-control-label">Número</label>
                            <input type="text" name="cliente_numero" class="form-control" value="{{$nota->cliente_numero}}" id="cliente_numero" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Complemento</label>
                            <input type="text" name="cliente_complemento" class="form-control" value="{{$nota->cliente_complemento}}" id="cliente_complemento" readonly>
                        </div>

                        <div class="col-md-2 form-group">
                            <label class="form-control-label">CEP</label>
                            <input type="text" name="cliente_cep" class="form-control" value="{{$nota->cliente_cep}}" id="cliente_cep" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label class="form-control-label">Observação</label>
                        <textarea name="observacao" rows="5" class="form-control" id="observacao" value="{{old('observacao', $nota->observacoes)}}"></textarea>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.notasFiscais.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    {{-- <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button> --}}
                </div>
            </form>
        </div>
    </div>

    <div id="modal"></div>
@endsection

@push('scripts')
    <script>

        $('.modal_pesquia_conta').on('click', function(){
            var url = "{{ route('instituicao.notasFiscais.pesquisaContaReceber') }}";
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalContaReceber';

            $('#loading').removeClass('loading-off');
            $('#modal').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
                $("#cpf").setMask()
            });
        })

        $("#valorTotalnota").on("change", function(){
            var valor_nota =  $(this).val().replace('.', '').replace(',', '.')
            var aliquota_iss = $("#aliquota_iss").val().replace('.', '').replace(',', '.')
            var valor_iis = (valor_nota * (aliquota_iss/100)).toFixed(2);

            $("#valorIis").val(valor_iis.replace(".",","))
        })
    </script>
@endpush
