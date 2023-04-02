<div class="form-group {{$tamanho}}">
    <label class="form-control-label">{{$titulo}}:</label>
    <select class="form-control" name="{{$nome}}">
        @foreach ($cids as $item)            
            <option value="{{$item->id}}">{{$item->descricao}}</option>
        @endforeach
    </select>
</div>