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

            <form action="{{ route('instituicao.planosContas.update', [$planoConta]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class="col-md-9 form-group @if($errors->has('plano_conta_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Plano de contas Pai</label>
                        <input type="text" class="form-control p-0 m-0" value="{{ ($pai) ? $pai->codigo.' - '.$pai->descricao : 'Nenhum' }}" name="codigo" id="codigo" disabled>
                        @if($errors->has('plano_conta_id'))
                            <small class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('codigo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Código de Hierarquia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control p-0 m-0" value="{{ $planoConta->codigo }}" name="codigo" id="codigo" disabled>
                        @if($errors->has('codigo'))
                            <small class="form-control-feedback">{{ $errors->first('codigo') }}</small>
                        @endif
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control p-0 m-0" value="{{ $planoConta->descricao }}"
                            name="descricao" id="descricao" >
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('padrao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Padrão <span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('padrao')) form-control-danger @endif" name="padrao" id="padrao" disabled>
                            <option value="1" {{ ($planoConta->padrao == 1) ? 'selected' : '' }} >Débito</option>
                            <option value="0" {{ ($planoConta->padrao == 0) ? 'selected' : '' }} >Crédito</option>
                        </select>
                        @if($errors->has('padrao'))
                            <small class="form-control-feedback">{{ $errors->first('padrao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('rateio_auto')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rateio automático <span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('rateio_auto')) form-control-danger @endif" name="rateio_auto" id="rateio_auto" readonly>
                            <option value="1" {{ ($planoConta->rateio_auto == 1) ? 'selected' : '' }} >Sim</option>
                            <option value="0" {{ ($planoConta->rateio_auto == 0) ? 'selected' : '' }} >Não</option>
                        </select>
                        @if($errors->has('rateio_auto'))
                            <small class="form-control-feedback">{{ $errors->first('rateio_auto') }}</small>
                        @endif
                    </div>
                </div>

                @if($errors->has('porcentagem'))
                    <div id="erro-porcentagem" class="alert alert-danger p-2 col-md-6 col-sm-8">{{ $errors->first('porcentagem') }}</div>
                @endif
                <div class='rateio' style="{{ ($planoConta->rateio_auto == 0) ? 'display: none;' : 'display: block;' }}">
                    <div class="row">
                        @include('instituicao.planos_contas.centro_custo_editar')

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

        $(document).ready(function() {
            if(erro) {
                setTimeout(function(){
                    $('#erro-porcentagem').hide()
                }, 3000)
            }
        })
    </script>
@endpush
