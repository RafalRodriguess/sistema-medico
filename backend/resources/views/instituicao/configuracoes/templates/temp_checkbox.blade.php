<div class="{{$tamanho}}">
    <label class="form-control-label">{{$titulo}}:</label>
    <div class="row">
        @foreach ($options as $item)
            <div class="form-group col-md-12">
                <input type="checkbox" name="{{$item['nome']}}" value="{{$item['valor']}}" class="filled-in" />
                <label for="nf_imposto">{{$item['nome']}}<label>
            </div>
        @endforeach
    </div>
</div>