@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Dispositivos do Usuário',
        'breadcrumb' => [
            'Usuário' => route('usuarios.index'),
            'Dispositivos',
        ],
    ])
    @endcomponent

    @foreach ($dispositivos as $item)
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    
                    <h6 class="card-title">{{ strtoupper(json_decode($item->device_data)->manufacturer) }} . ultimo acesso {{ $item->last_used_at->diffForHumans() }}</h6>
                
                    <h4 class="card-title">{{ json_decode($item->device_data)->model}}</h4>
                    <h6 class="card-subtitle">
                        @if (json_decode($item->device_data)->platform == 'android')
                            <i class="mdi mdi-android"></i>
                        @else
                        <i class="mdi mdi-apple"></i>
                        @endif
                        {{ json_decode($item->device_data)->platform}}
                    </h6>
                    <div style="border-bottom: 1px solid; border-color: #00000047;"></div> 
                    <p class="card-subtitle" style="margin-top: 10px; font-size: 13px;">criado em {{ $item->created_at->format('d/m/Y') }}</p>
                    <a href="{{route('usuario.usuarioDevice', [$usuario])}}" data-submit="remove" class="btn btn-danger waves-effect waves-light m-r-10"><i class="ti-trash"></i>Remover</a>
                    <form id="remove-device" action="{{ route('usuario.usuarioDevice', [$usuario]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="id" value="{{ $item->id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
@endsection

@push('scripts');
<script>
    $("[data-submit='remove']").on('click', function(){
        
        event.preventDefault(); 
        Swal.fire({   
            title: "Confirmar exclusão?",   
            text: "Ao confirmar você estará excluindo o registro permanente!",   
            icon: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sim, confirmar!",   
            cancelButtonText: "Não, cancelar!",
        }).then(function (result) {   
            if (result.value) {     
                document.getElementById('remove-device').submit();
            } 
        });
        
    })
</script>
@endpush