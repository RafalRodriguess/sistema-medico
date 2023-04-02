
<form action="{{ route('instituicao.financeiro.cc.update', [$centro_custo]) }}" method="post">
    @method('put')
    @csrf

    <div class="row">
        <div class="col-md-8 form-group @if($errors->has('pai_id')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Centro de Custo Pai</label>
            <input type="number" class="form-control" readonly
                value="@if(isset($centro_custo->pai()->codigo))
                    {{ $centro_custo->pai()->codigo }} {{ $centro_custo->pai()->descricao }}
                @else Nenhum @endif">
            @if($errors->has('pai_id'))
                <small class="form-control-feedback">{{ $errors->first('pai_id') }}</small>
            @endif
        </div>

        <div class=" col-md-4 form-group @if($errors->has('grupo_id')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Grupo</label>
            <select class="form-control p-0 m-0" name="grupo_id">
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo }}" @if ($centro_custo->grupo_id == $grupo)
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
            <input type="text" class="form-control p-0 m-0"
                value="{{ $centro_custo->codigo }}"
                name="codigo">
            @if($errors->has('codigo'))
                <small class="form-control-feedback">{{ $errors->first('codigo') }}</small>
            @endif
        </div>

        <div class="col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('descricao', $centro_custo->descricao) }}"
                name="descricao">
            @if($errors->has('descricao'))
                <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group @if($errors->has('email')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Email</label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('email', $centro_custo->email) }}"
                name="email">
            @if($errors->has('email'))
                <small class="form-control-feedback">{{ $errors->first('email') }}</small>
            @endif
        </div>

        <div class="col-md-6 form-group @if($errors->has('gestor')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Gestor</label>
            <input type="text" class="form-control p-0 m-0" value="{{ old('gestor', $centro_custo->gestor) }}"
                name="gestor">
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
                <option @if(old('setor_exame_id', $centro_custo->setor_exame_id) == $setor_exame->id) selected="selected" @endif
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
                    @if(old('lancamento')=="1" || $centro_custo->lancamento==1)
                        checked
                    @endif id="lancamentoCheck">
                <label class="form-check-label" for="lancamentoCheck">Aceita Lançamento</label>
            </div>
        </div>
        <div class="col-sm-6 p-0 pt-3 m-0">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="ativo" value="1"
                    @if(old('ativo')=="1" || $centro_custo->ativo==1)
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
