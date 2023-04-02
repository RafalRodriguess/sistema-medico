@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Configuração Prontuário',
        'breadcrumb' => [
            'Configuração Prontuário' => route('instituicao.configuracaoProntuario.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.configuracaoProntuario.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" required value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-12" style="text-align: center">
                        <button type="button" class="btn btn-primary waves-effect waves-light m-r-10 add_campos"><i class="mdi mdi-format-align-justify"></i> Adicionar campos</button>
                    </div>
                    <hr style="width: 100%">
                    <div class="campos" style="display: none"></div>
                    <div class="exibir col-md-12">
                        <div class="row">

                            <div class="last-div"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.configuracaoProntuario.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal_add_campos">
        @include('instituicao.configuracoes.configuracao_prontuario.modal')
    </div>
@endsection

@push('scripts')
    <script>
        $(".add_campos").on('click', function() {
            $(".modal_add_campos").find('#modalAdicionarCampo').modal('show');
        })

        $("#tipo_item").on('change', function(){
            if($("#tipo_item").val() != ""){
                $(".dados-campos").removeClass("disabled")
                $('.nav a[href="#dados-campos"]').tab('show');
            }
        })
    </script>
@endpush
