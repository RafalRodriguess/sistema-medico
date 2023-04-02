<style>
    .senha-impressa {
        width: 100vw;
        display: flex;
        flex-flow: column;
        align-items: center;
        height: 100vh;
    }
    .senha-impressa > * {
        display: block;
        margin: 0 0 1rem 0;
    }
    hr {
        width: 100%;
        border-width: 0 0 2px 0;
    }
    .numero {
        margin: 2rem 0 0.5rem 0;
    }
    .fila {
        margin: 0 0 2rem 0;
    }
</style>
<div class="senha-impressa">
    <h3>{{ $senha->fila->totem->instituicao->nome }}</h3>
    <h1 class="numero">SENHA {{strtoupper($senha->fila->filaTriagem->identificador) . $senha->valor}}</h1>
    <p class="fila"><b>FILA</b> {{ $senha->fila->filaTriagem->descricao }}</p>
    
    <p>{{ (new \DateTime($senha->horario_retirada))->format('d/m/Y') }}</p>
    <p>Horário de retirada {{ (new \DateTime($senha->horario_retirada))->format('H:i') }}</p>
</div>