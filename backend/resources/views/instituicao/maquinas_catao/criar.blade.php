@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Maquina de cartão',
        'breadcrumb' => [
            'Maquinas de cartão' => route('instituicao.maquinasCartoes.index'),
            'Nova',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.maquinasCartoes.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-md-8 @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</label>
                        <input type="text" name="descricao" class="form-control" value="{{old('descricao')}}">
                        @if ($errors->has('descricao'))
                            <div class="form-control-feedback">{{$errors->first('descricao')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4  @if($errors->has('codigo')) has-danger @endif">
                        <label class="form-control-label">Código: *</label>
                        <input type="text" name="codigo" class="form-control" value="{{old('codigo')}}">
                        @if ($errors->has('codigo'))
                            <div class="form-control-feedback">{{$errors->first('codigo')}}</div>
                        @endif
                    </div>

                    <div class="row col-sm-12">
                        <h4 class="col-sm-12">Débito</h4>
                        <div class='form-group col-md-4'>
                            <label class='form-control-label'>taxa: *</label>
                            <input class='form-control' alt='money' type='text' name='taxa_debito'>
                        </div>
                        <div class='form-group col-md-3'>
                            <label class='form-control-label'>Dias parcela:</label>
                            <input class='form-control' alt='numeric' type='text' name='dias_parcela_debito' value="0">
                        </div>

                    </div>

                    <div class="row col-sm-12">
                        <h4 class="col-sm-12">Crédito</h4>
                        <ol class="col-sm-12 ml-3 parcelas">
                            <li class="form-group col-sm-4">
                                <label>Parcela</label><br>
                                <label class='form-control-label'>taxa: *</label>
                                <input class='form-control taxa' alt='money' type='text' name='taxa_credito[]'>
                            </li>
                        </ol>
                    </div>

                    {{-- <div class="row col-sm-12 parcelas">
                        <div class='form-group col-md-4'>
                            <label class='form-control-label'>Forma de pagamento: *</label>
                            <select class='form-control select_parcela selectfild2' name='taxa[0][tipo]' style='width: 100%'>
                                <option value=''>Selecione</option>
                                <option value='cartao_credito'>Cartão de crédito</option>
                                <option value='cartao_debito'>Cartão de crédito</option>
                            </select>
                        </div>

                        <div class='form-group col-md-4'>
                            <label class='form-control-label'>Parcela: *</label>
                            <input class='form-control' alt='numeric' type='text' name='taxa[0][parcela]'>
                        </div>

                        <div class='form-group col-md-4'>
                            <label class='form-control-label'>taxa: *</label>
                            <input class='form-control' alt='decimal' type='text' name='taxa[0][taxa]'>
                        </div>

                        
                    </div> --}}

                    <div class='form-group col-md-2'>
                        <i class="fa fa-plus-circle help" onclick="addParcela()" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar parcela"></i>
                        <i class="fa fa-minus-circle help" onclick="removeParcela()" data-toggle="tooltip" data-placement="right" title="" data-original-title="Remover parcela"></i>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-3'>
                        <label class='form-control-label'>Dias parcela: </label>
                        <input class='form-control' alt='numeric' type='text' name='dias_parcela_credito' value="0">
                    </div>
                </div>

                    
                <div class="form-group text-right">
                    <a href="{{ route('instituicao.maquinasCartoes.index') }}">
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
        $(document).ready(function(){

        })

        function addParcela(){
            html = "";

            html += "<li class='form-group col-sm-4'><label>Parcela</label><br><label class='form-control-label'>taxa: *</label><input class='form-control taxa' alt='money' type='text' name='taxa_credito[]'></li>"

            $(".parcelas").append(html);
            $('.taxa').setMask();
        }

        function removeParcela(){
            $(".parcelas").find('li:last').remove();
        }
    </script>
@endpush
