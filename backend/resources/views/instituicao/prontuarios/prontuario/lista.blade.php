<style>
    .menu-lista{
        /* margin-bottom: 10px;
        margin-top: 10px; */
        background: #ababab61;
        color: white;
    }
    .item-lista{
        /* border: 1px solid #f3f1f1eb; */
        padding: 7px;
    }
    .item-lista .item{
        background: #ababab61;
        padding: 10px;
    }
    .item-lista .texto{
        padding: 10px;
        text-align: justify;
    }
    .list-group-item{
        padding: 0px 0px 0px 2px !important;
        border-radius: 5px;
    }
    hr{
        margin: 3px !important;
    }
</style>
<div data-spy="scroll" data-target="#list-example" data-offset="0" class="position-relative mt-2" style="height: 500px; overflow: auto;">
    @if ($prontuario != null)
        <input type="hidden" name="prontuarioId" id="prontuarioId" value="{{$prontuario->id}}">
    @endif
    @foreach ($prontuarios as $item)    
        <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($item->created_at) ) }} - {{$item->usuario->nome}}</a>
        <div class="item-lista">
            {{-- <h4 id="list-item-1" class="item">Prontuário</h4> --}}
            @if ($item->prontuario['tipo'] == 'old')
                <p class="texto">{!!$item->prontuario['obs']!!}</p>
            @else
                @if (array_key_exists('queixa_principal', $item->prontuario))
                    @if ($item->prontuario['queixa_principal'])
                        <p><b>Queixa principal:</b></p>
                        <p>{{$item->prontuario['queixa_principal']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('h_m_a', $item->prontuario))
                    @if ($item->prontuario['h_m_a'])
                        <p><b>H.M.A:</b></p>
                        <p>{{$item->prontuario['h_m_a']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('h_p', $item->prontuario))
                    @if ($item->prontuario['h_p'])
                        <p><b>H.P:</b></p>
                        <p>{{$item->prontuario['h_p']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('h_f', $item->prontuario))
                    @if ($item->prontuario['h_f'])
                        <p><b>H.F:</b></p>
                        <p>{{$item->prontuario['h_f']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('hipotese_diagnostica', $item->prontuario))
                    @if ($item->prontuario['hipotese_diagnostica'])
                        <p><b>Hipótese diagnôstica:</b></p>
                        <p>{{$item->prontuario['hipotese_diagnostica']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('exame_fisico', $item->prontuario))
                    @if ($item->prontuario['exame_fisico'])
                        <p><b>Exame fisico:</b></p>
                        <p>{{$item->prontuario['exame_fisico']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('conduta', $item->prontuario))
                    @if ($item->prontuario['conduta'])
                        <p><b>Conduta:</b></p>
                        <p>{{$item->prontuario['conduta']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('obs', $item->prontuario))
                    @if ($item->prontuario['obs'])
                        <p><b>Observações:</b></p>
                        <p>{{$item->prontuario['obs']}}</p>
                        <hr>
                    @endif
                @endif
                @if (array_key_exists('cid', $item->prontuario))
                    @if ($item->prontuario['cid'] != "")
                        <p><b>CID:</b></p>
                        <p>{{$item->prontuario['cid']['texto']}}</p>
                        <hr>
                    @endif
                @endif
            @endif
        </div>
    @endforeach
</div>
    