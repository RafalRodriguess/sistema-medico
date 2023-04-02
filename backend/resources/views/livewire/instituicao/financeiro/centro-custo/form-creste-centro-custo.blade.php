


<form action="{{ route('instituicao.financeiro.cc.store') }}" method="post">
    @csrf

    <div class="row">
        <div wire:ignore class="col-md-8 form-group @if($errors->has('pai_id')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Centro de Custo Pai</label>
            <select class="form-control p-0 m-0 select2Live" name="pai_id"
                wire:init="getCurrentlyCode"
                wire:change="getCurrentlyCode($event.target.value)" >
                <option value="0" selected>Nenhum</option>
                @foreach ($centros_custos as $centro_custo)
                    <option value="{{ $centro_custo->id }}">{{ $centro_custo->codigo }} {{ $centro_custo->descricao }}</option>
                @endforeach
            </select>
            @if($errors->has('pai_id'))
                <small class="form-control-feedback">{{ $errors->first('pai_id') }}</small>
            @endif
        </div>




        <div class=" col-md-4 form-group @if($errors->has('grupo_id')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Grupo</label>
            <select class="form-control p-0 m-0" name="grupo_id" @if ($pai_grupo_id!=null)
                readonly aria-disabled="true" style="pointer-events: none; touch-action: none;"
            @endif>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo }}" @if ($pai_grupo_id==$grupo)
                        selected
                    @endif>{{ App\CentroCusto::getGrupoTexto($grupo) }}</option>
                @endforeach
            </select>
            @if($errors->has('grupo_id'))
                <small class="form-control-feedback">{{ $errors->first('grupo_id') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 form-group @if($errors->has('codigo')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Código de Hierarquia <span class="text-danger">*</span></label>
            <input type="text" class="form-control p-0 m-0" value="{{ $codigo }}" name="codigo">
            @if($errors->has('codigo'))
                <small class="form-control-feedback">{{ $errors->first('codigo') }}</small>
            @endif
        </div>

        <div class="col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Descrição</label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('descricao') }}"
                name="descricao" id="descricao" wire:model="descricao">
            @if($errors->has('descricao'))
                <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group @if($errors->has('email')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Email</label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('email') }}"
                name="email" id="email" wire:model="email">
            @if($errors->has('email'))
                <small class="form-control-feedback">{{ $errors->first('email') }}</small>
            @endif
        </div>

        <div class="col-md-6 form-group @if($errors->has('gestor')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Gestor</label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('gestor') }}"
                name="gestor" id="gestor" wire:model="gestor">
            @if($errors->has('gestor'))
                <small class="form-control-feedback">{{ $errors->first('gestor') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class=" col-md-4 form-group @if($errors->has('setor_exame_id')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Setor de exames</label>
            <select class="form-control p-0 m-0" name="setor_exame_id">
                <option value="">Nenhum</option>
                @foreach ($setores_exame as $setor_exame)
                    <option @if(old('setor_exame_id') == $setor_exame->id) selected="selected" @endif
                        value="{{ $setor_exame->id }}">{{ $setor_exame->nome }}</option>
                @endforeach
            </select>
            @if($errors->has('setor_exame_id'))
                <small class="form-control-feedback">{{ $errors->first('setor_exame_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 p-0 pt-3 m-0">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="lancamento" value="1"
                    @if(old('lancamento')=="1")
                        checked
                    @endif id="lancamentoCheck">
                <label class="form-check-label" for="lancamentoCheck">Aceita Lançamento</label>
            </div>
        </div>
        <div class="col-sm-6 p-0 pt-3 m-0">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="ativo" value="1"
                    @if(old('ativo')=="1")
                        checked
                    @endif id="ativoCheck">
                <label class="form-check-label" for="ativoCheck">Ativo</label>
            </div>
        </div>
    </div>



    <div class="form-group text-right pb-2">
        <a href="{{ route('instituicao.financeiro.cc.index') }}">
            <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
        </a>
        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            window.livewire.hook('afterDomUpdate', () => {
                // $('.select2Live').select2();
                $(".select2Live").each(function () {
                    var $select = $(this);
                    if (!$(this).attr('wire:change')) {
                        $select.select2();
                        return;
                    }

                    var $id = $(this).parents('[wire\\:id]').attr('wire:id');
                    $select.select2().on('select2:select', function (e) {
                        // window.livewire.find($id).set($(this).attr('wire:change'), e.params.data.id);
                        livewire.emit('getCurrentlyCode', e.params.data.id)
                    });
                });
            });
        });
    </script>
@endpush
