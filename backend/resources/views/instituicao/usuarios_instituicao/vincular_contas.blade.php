@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "vincular contas a usuário #{$usuario->id} {$usuario->nome}",
        'breadcrumb' => [
            'Usuários' => route('instituicao.instituicoes_usuarios.index'),
            'vincular contas',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.instituicoes_usuarios.salvarVinculoContas', [$usuario]) }}" method="post" >
                @csrf

                <input type="hidden" name="usuario_id" value="{{ old('usuario_id', $usuario->id) }}">
                
                <div class="form-group @if($errors->has('usuario_id')) has-danger @endif">
                    <h3 class="form-control-label">Contas</h3>
                    
                    @foreach($contas as $item)
                        <div class="checkbox checkbox-primary p-t-0">
                            <input id="checkbox-{{$item->id}}" type="checkbox" name="contas[]" value="{{$item}}" @if(in_array($item->id, $contas_vinculadas)) checked @endif>
                            <label for="checkbox-{{$item->id}}"> {{$item->descricao}} </label>
                        </div>
                    @endforeach
                    
                </div>

                <div class="form-groupn text-right">
                    <a href="{{ route('instituicao.instituicoes_usuarios.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts');
    <script>

        $( document ).ready(function() {
            $("form").submit(function(e){
                e.preventDefault()

                var formData = new FormData($(this)[0]);

                $.ajax("{{ route('instituicao.instituicoes_usuarios.salvarVinculoContas', [$usuario]) }}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
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
                    },
                    error: function (response) {
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


        })
    </script>
@endpush
