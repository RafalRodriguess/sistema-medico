<div class="form-group {{$tamanho}}">
    <label class="form-control-label">{{$titulo}}:</label>
    <div class="demo-radio-button">
        @foreach ($options as $item)
            <input name="{{$nome}}" type="radio" id="{{$item['radio']}}" value="{{$item['valor']}}"/>
            <label for="{{$item['radio']}}">{{$item['nome']}}</label>
        @endforeach
    </div>
</div>