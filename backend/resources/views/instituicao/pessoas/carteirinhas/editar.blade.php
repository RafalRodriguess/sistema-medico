@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Carteirinha de convenio',
        'breadcrumb' => [
            "Carteirinha do paciente #$pessoa->id" => route('instituicao.carteirinhas.index', [$pessoa]),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.carteirinhas.update', [$pessoa, $carteirinha]) }}" method="post">
                @csrf
                @method('put')
                <div class="row p-2">
                    <input type='hidden' value='{{ $pessoa->id }}' name='pessoa_id'>
                    <div class="col-sm">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Convênio <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="convenio_id" id='convenio_id'>
                                <option value='' disabled>Selecione o convenio</option>
                                @foreach ($convenios as $item)
                                    <option value="{{ $item->id }}" @if(old('convenio_id', $carteirinha->convenio_id) == $item->id) selected @endif >{{ $item->nome }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('convenio_id'))
                                <small class="form-text text-danger">{{ $errors->first('convenio_id') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Plano <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="plano_id" id='plano_id'>
                                <option value='' disabled>Selecione o plano</option>
                                @foreach ($planos as $item)
                                    <option value="{{ $item->id }}" @if(old('plano_id', $carteirinha->plano_id) == $item->id) selected @endif >{{ $item->nome }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('plano_id'))
                                <small class="form-text text-danger">{{ $errors->first('plano_id') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm">
                        <label class="form-control-label p-0 m-0">Nº Carteirinha <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="carteirinha" value="{{old('carteirinha', $carteirinha->carteirinha)}}">
                        @if($errors->has('carteirinha'))
                            <small class="form-text text-danger">{{ $errors->first('carteirinha') }}</small>
                        @endif
                    </div>

                    <div class="col-sm">
                        <label class="form-control-label p-0 m-0">Validade <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="validade" value="{{old('validade', $carteirinha->validade)}}">
                        @if($errors->has('validade'))
                            <small class="form-text text-danger">{{ $errors->first('validade') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.carteirinhas.index', [$pessoa, $carteirinha]) }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" id="salvar" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            // getPlanos()
        })

        $('#convenio_id').on('change', function(){
            getPlanos()
        })

        function getPlanos(){
           id = $('#convenio_id').val();
            if(id != ''){
            $.ajax({
                        url: "{{route('instituicao.carteirinhas.getPlanos', ['convenio_id' => 'Id'])}}".replace('Id', id),
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            paciente_id: id
                        },
                        success: function(retorno){

                            $('#plano_id').find('option').filter(':not([value=""])').remove();
                            for (i = 0; i < retorno.length; i++) {
                                $('#plano_id').append('<option value="'+ retorno[i].id +'">' + retorno[i].nome + '</option>');
                            }
                        }
                    })
           }
        }
    </script>
@endpush
