<div class="audiotoria">
    <div class="row">
        <div class="col-sm-12 m-t-20">
            <table class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                <thead>
                    <tr>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Data</th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Localização</th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descrição</th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="4">Duração</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($lista as $item)
                        @php 
                            $localizacao = (in_array($item->status, [ 'finalizado_medico', 'em_atendimento'])) ? 'Consultório' : 'Hospital';
                        @endphp
                        
                        <tr>
                            <td>{{date("d/m/Y H:i:s", strtotime($item->data))}}</td>
                            <td>{{$localizacao}}</td>
                            <td><b>{{App\AuditoriaAgendamento::getLogTextos($item->log)}}</b> pelo {{($localizacao == 'Consultório') ? 'profissional' : 'usuário'}} <b>{{$item->usuarios->nome}}</b> </td>
                            <td>
                                @if(!empty($hora))
                                    @php
                                        $total_secs = strtotime($hora) - strtotime($item->data);

                                        $horas = floor($total_secs/3600);

                                        $mins = floor(($total_secs % 3600)/60);

                                        $secs = ($total_secs % 3600) % 60;

                                        $duracao = str_pad($horas, 2, 0, STR_PAD_LEFT).":".str_pad($mins, 2, 0, STR_PAD_LEFT).":".str_pad($secs, 2, 0, STR_PAD_LEFT)
                                    @endphp
                                    {{$duracao}}
                                @endif
                            <td>
                        </tr>

                        @php 
                            $hora = $item->data;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>