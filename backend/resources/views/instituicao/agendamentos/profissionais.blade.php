<ul class="list-group list-group-flush listaProfissionais">
    @foreach($profissionais as $item)
        <li class="list-group-item profissional" data-id="{{$item['prestador_especialidade_id']}}"><small>{{$item['profissional']}}<br>Inicio: {{$item['inicio']}} - Fim: {{$item['fim']}}</small></li>
    @endforeach
</ul>
