<div class="form-group {{$tamanho}}">
    <label class="form-control-label">{{$titulo}}:</label>
    <select class="form-control" name="{{$nome}}">
        @foreach ($options as $item)            
            <option value="{{$item['valor']}}">{{$item['descricao']}}</option>
        @endforeach
    </select>
</div>