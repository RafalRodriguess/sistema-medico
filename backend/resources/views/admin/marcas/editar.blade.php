@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Marca',
        'breadcrumb' => [
            'Marcas' => route('marcas.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('marcas.update', [$marca]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $marca->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>

                {{-- <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label">Imagem </label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Logo" >
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" id="image"
                            @if ($marca->imagem)
                                src="{{ \Storage::url($marca->imagem) }}"
                            @else
                                src="{{ asset('material/assets/images/default_image.png') }} "
                            @endif>
                            <input type="file" class='sr-only'  id="input" >

                    </label>
                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div> --}}


                <div class="form-group text-right">
                         <a href="{{ route('marcas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal inmodal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                <h5 class="modal-title">Defina a logo</h5>

            </div>
            <div class="modal-body">
                <div >
                <img style="max-width: 100%;" id="imageModal" src="">
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" style='margin:0;' class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="crop">Definir</button>

            </div>
            </div>
        </div>
    </div>
@endsection

