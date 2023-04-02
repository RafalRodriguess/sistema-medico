<input type="hidden" name="solicitacao_id" id="solicitacao_id" value="">

<div class="row">
    <div class="col-md-4 col-sm-10 form-group @if ($errors->has('destino')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Destino <span class="text-danger">*</span></label>
        <input type="hidden" name="destino" readonly value="1">
        <input readonly class="form-control" value="Paciente"></option>
        </select>
    </div>

    <div class="col-md-8 col-sm-10 form-group">
        <label class="form-control-label p-0 m-0">Estoque de origem <span class="text-danger">*</span></label>
        <select id="estoque-origem-select" name="estoque_origem_id" style="width: 100%"
            class="form-control select2basic">
            <option selected hidden disabled>Selecione ...</option>
            @foreach ($estoques as $estoque)
                <option @if (old('estoque_origem_id') == $estoque->id) selected="selected" @endif value="{{ $estoque->id }}">{{ $estoque->descricao }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-10 form-group">
        <label class="form-control-label p-0 m-0">Atendimento <span class="text-danger">*</span></label>
        <input name="agendamento_atendimentos_id" id="agendamento_atendimentos_id" type="hidden" value='{{$agendamento->atendimento[0]->id}}'>
        <input class="form-control" id="agendamento_atendimentos_name" value="#{{$agendamento->id}} {{$agendamento->data->format("d/m/Y - H:i")}} {{$paciente->nome}}" >
    </div>

    <div class="col-md-6 col-sm-10 form-group">
        <label class="form-control-label p-0 m-0">Prestador solicitante <span class="text-danger">*</span></label>
        <select id="prestador-select" name="instituicoes_prestadores_id" style="width: 100%"
            class="form-control  @if ($errors->has('instituicoes_prestadores_id')) form-control-danger @endif">
            <option value="{{$usuario->prestador[0]->id}}" selected>{{$usuario->prestador[0]->prestador->nome}}</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-sm-10 form-group">
        <label class="form-control-label p-0 m-0">Observações</label>
        <textarea id="observacoes" name="observacoes" rows="2" class="form-control"></textarea>
    </div>
    <div class="col-md-4 pt-4">
        <div class="form-group d-flex flex-column justify-content-center">
            <div class="d-flex flex-wrap-revert align-items-center">
                <label class="form-control-label mr-2 mb-0">Urgente?</label>
                <input type="checkbox" name="urgente" id="urgente" class="form-control checkbox">
            </div>
        </div>
    </div>
</div>

<div class="row col-12">
    <div class="card col-12 px-0 py-3 shadow-none">
        <div class="form-group col-md-12 p-0">
            <div class="col-md-8">
                <label class="form-control-label p-0 m-0 @if($errors->has('produtos')) has-danger @endif">Produto</label>
                <div class="input-group">
                    <div class="col p-0">
                        <select id="produto-select" style="width: 100%" class="form-control @if($errors->has('produtos')) form-control-danger @endif"></select>
                    </div>
                    <div class="px-1">
                        <button onclick="addProduct()" type="button" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                @if ($errors->has('produtos'))
                    <small class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</small>
                @endif
            </div>
            <div class="mt-4 col-12 table-container">
                <table class="table table-bordered">
                    <colgroup>
                        <col style="width: auto">
                        <col style="width: 300px">
                        <col style="width: 300px">
                        <col style="width: 100px">
                        <col style="width: 50px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Classe</th>
                            <th>Unidade</th>
                            <th>Quantidade</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="produtos-container">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
