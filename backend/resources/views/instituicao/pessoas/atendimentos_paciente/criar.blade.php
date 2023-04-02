<div class="card-body">
                
    <form action="{{ route('instituicao.atendimentos_paciente.store', [$pessoa]) }}" id="form_atendimento_criar" method="post" enctype="multipart/form-data">
        @csrf
    
        <div class="row">

            <div class="form-group col-md-4">
                <label>Data atendimento *</label>
                <input type="datetime-local" class="form-control" id="data_atendimento" name="data_atendimento" readonly value="{{date('Y-m-d\TH:i')}}">
            </div>

            <div class="col-md-4 form-group">
                <label for="form-control-label">Motivo *</label>
                <select class="form-control select2Atendimento" style="width: 100%!important;" name="motivo_atendimento_id" id="motivo_atendimento_id">
                    @foreach ($motivos as $item)
                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-12 @if($errors->has('descricao')) has-danger @endif">
                <label class="form-control-label">Descricao</label>
                <textarea class="form-control" name="descricao" id="descricao" cols="30" rows="3">{{ old('descricao') }}</textarea>
                
                @if($errors->has('descricao'))
                    <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                @endif
            </div>
        </div>  
                
        <div class="form-group text-right">
            <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 cancelar_atendimento">Lista atendimentos</button>
            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
        </div>
    </form>
</div>

<script>
    $(".select2Atendimento").select2();
</script>