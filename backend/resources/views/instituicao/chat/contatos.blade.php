@php
    $cont = 0;
@endphp
@foreach ($contatos as $contato)
    @php
        $cont ++;
    @endphp
    <div class="contato" el-id="{{ $contato->id }}"> {{-- Id do contato destinatário --}}
        <div class="contato-wrapper">
            <div class="contato-header">
                @include('instituicao.chat.imagem-usuario', ['usuario' => $contato])
                @if($exibir_ultima_mensagem && !empty($contato->mensagem_id) && $contato->mensagem_visualizada == 0)
                    <div class="info-contato-expansivel">
                        <div @if(strlen($contato->nome) > 22) title="{{ $contato->nome }}" @endif class="contato-nome">{{ mb_strimwidth($contato->nome, 0, 22, '...') }}</div>
                        @php
                            $mensagem = \App\ChatMensagem::decifrarMensagem($contato->mensagem_conteudo, 27);
                            $data_hora = new \DateTime($contato->mensagem_data);
                        @endphp
                        <div class="mensagem mensagem-contato">
                            <div class="mensagem-texto p-0">{{ $mensagem }}</div>
                            <div class="mensagem-data">
                                <span class="hora">{{ $data_hora->format('H:i') }}h</span>
                                <span class="hora">{{ $data_hora->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="info-contato">
                        <div @if(strlen($contato->nome) > 30) title="{{ $contato->nome }}" @endif class="contato-nome">{{ mb_strimwidth($contato->nome, 0, 30, '...') }}</div>
                        @if($adicionar_contato ?? false)<button class="btn btn-sm button-adicionar-contato btn-info" el-id="{{ $contato->id }}"><i el-id="{{ $contato->id }}" class="fas fa-user-plus"></i></button>@endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
@if ($cont == 0)
    <span class="py-2 px-3 empty-contatos-list">Nenhuma conversa encontrada, inicie uma conversa através chat.</span>
@endif
