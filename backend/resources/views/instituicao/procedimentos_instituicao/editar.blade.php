@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => 'Editar Procedimento',
'breadcrumb' => [
'Procedimentos' => route('instituicao.procedimentos.index'),
'Editar',
],
])
@endcomponent


<div class="card">
    <div class="card-body">
        <form action="{{ route('instituicao.procedimentos.update', [$procedimento]) }}" method="post">
        @method('put')
            @csrf
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-4 form-group">
                        <label class="form-control-label">Procedimento</label>
                        <h4>{{$procedimento->procedimento->descricao}}</h4>
                    </div>

                    <div class="col-md-6 form-group @error('grupo_id') has-danger @enderror">
                        <label class="form-control-label">Grupo *</label> <br>
                        <select id='grupo' name="grupo_id" class="form-control select2 @error('grupo_id') form-control-danger @enderror">
                            <option selected value="{{ $procedimento->grupoProcedimento->id }}">{{$procedimento->grupoProcedimento->nome}}</option>
                        </select>
                        @error('grupo_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($procedimento->procedimento->tipo=="exame")
                    <div class="form-group">
                        <div class="col-md-12 @error('tipo') has-danger @enderror">
                        <label class="control-label">Forma de atendimento</label>
                        </div>
                        <div class="col-md-12">
                            <label class="i-checks">
                                <input type="radio" name="tipo" @if($procedimento->tipo=='unico') checked @endif value="unico" > Hora marcada
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="i-checks">
                                <input type="radio" name="tipo" @if($procedimento->tipo=='avulso') checked @endif value="avulso"  > Check-in
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="i-checks">
                                <input type="radio" name="tipo" @if($procedimento->tipo=='ambos') checked @endif value="ambos" > Ambos
                            </label>
                        </div>
                        @error('tipo')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="row">

                        <div id="modalidade_exame_select" class="col-md-6 form-group @if($errors->has('modalidades_exame_id')) has-danger @endif">
                                <label class="form-control-label">Modalidade: *</label>
                                <select name="modalidades_exame_id" class="form-control
                                @if($errors->has('modalidades_exame_id')) form-control-danger @endif
                                " id="">
                                <option value="">Nenhuma</option>
                                @foreach ($modalidades as $modalidade)
                                    <option value="{{ $modalidade->id }}" @if(old('modalidades_exame_id', $procedimento->modalidades_exame_id) == $modalidade->id) selected="selected" @endif>{{ $modalidade->sigla }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('modalidades_exame_id'))
                            <div class="form-control-feedback">{{ $errors->first('modalidades_exame_id') }}</div>
                            @endif
                        </div>
                    </div>
                @endif


                <div class=" col-md-12 form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.procedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <a href="{{ route('instituicao.procedimentos.index') }}">
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>

    $('input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	})

    $( document ).ready(function() {


        $('#grupo').select2({
            allowClear: true,
            tags: false,
            multiple:false,
            language: "pt-BR",
            placeholder: "Selecione o grupo",
            ajax:{
                url: '{{route("instituicao.getgrupobyprocedimento")}}',
                type: 'post',
                dataType: 'json',
                data: function(params){
                    return{
                        nome: params.term,
                        procedimento: {{$procedimento->procedimento->id}},
                        procedimentoInstituicao: {{$procedimento->id}},
                        '_token': '{{csrf_token()}}',
                    };
                },
                processResults: function(data){
                    return{
                        results: $.map(data,function(obj){
                            return {id: obj.id, text: obj.nome};
                        })
                    }
                }
            },
        })


    })
</script>
@endpush
