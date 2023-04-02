<div wire:poll.15000ms>
    <div wire:loading.class.remove="d-none" class="d-none col-12 text-center" wire:loading.class="ui ui-vazio" >
        {{-- <span style='font-size: 100px;' class="mdi mdi-refresh"></span> --}}
    </div>

    <div wire:loading.remove>
        @foreach ($secoes_painel as $secao)
            <section class="bordered-section @if(!empty($secao['items'] && $secao['items']['result'])) blink @endif">
                <div class="col-12 p-0 d-flex border-bottom bg-light">
                    <div class="col-4">
                        PACIENTE
                    </div>
                    <div class="col-4 text-center">
                        {{ $secao['area']->titulo }}
                    </div>
                    <div class="col-4 text-right">
                        {{ $secao['area']->local }}
                    </div>
                </div>
                <div class="col-12 p-4 d-flex border-bottom">
                    @if (!empty($secao['items']) && !empty($secao['items']['registro']->senha))
                        <div class="col-4">
                            {{ $secao['items']['registro']->senha->senha }}
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4 text-right">
                            {{ $secao['items']['registro']->local }}
                        </div>
                    @endif
                </div>
                <div class="col-12 p-2 bg-light"></div>
            </section>
        @endforeach
    </div>

    <div class="row text-center" style="padding: 20px">
        <div class="col-3 offset-5 p-0 d-flex" >
            <img src="{{ asset('material/assets/images/asasaude-blue.png') }}" style="max-width: 135px;" alt="" />
        </div>
    </div>

</div>
@push('estilos')
    <style>
        .footer {
            display: none;
        }

        .page-wrapper {
            height: 100vh;
        }

        .bordered-section {
            border: 2px solid #dee2e6;
            border-top: 2px solid #f8f9fa;
            transition: 1s ease-in-out;
        }

        .blink {
            border-color: red;
            color: inherit;
            animation: blink 1.25s steps(1) infinite;
            -webkit-animation: blink 1.25s steps(1) infinite;
        }
        @keyframes blink { 50% { 
            border: 2px solid #dee2e6;
            border-top: 2px solid #f8f9fa;
        } }
        @-webkit-keyframes blink { 50% { 
            border: 2px solid #dee2e6;
            border-top: 2px solid #f8f9fa;
        } }
    </style>
@endpush
@push('scripts')
    <script>
        $(function() {
            setTimeout(() => {
                $('.blink').removeClass('blink')
            }, 3000)
        })
    </script>
@endpush