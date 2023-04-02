<div class="d-flex justify-content-center">
    <div class="spinner-border" role="status" id="load">
        <span class="visually-hidden"></span>
    </div>
</div>
<div id="div-memed" width='820px' height='700px'></div>
<div id="api_memed"></div>

<script type="text/javascript">
    $(document).ready(function(e) {
        setMemed();
        // setPaciente();
        // blockAcaoUsario();
        // showPrescricao();
    })

    function setMemed () {
        var script = document.createElement('script');

        // script.dataset.color = 'COR_PRIMARIA_EM_HEXADECIMAL';
        script.dataset.token = "{{$token}}";
        script.dataset.container = "div-memed";

        script.src = 'https://memed.com.br/modulos/plataforma.sinapse-prescricao/build/sinapse-prescricao.min.js';
        script.setAttribute('id', 'js-memed-id');
        
        // Aguarde o carregamento do Sinapse Prescrição
        // para poder utilizar o `MdSinapsePrescricao` disponível globalmente via `window`
        script.addEventListener('load', function () {
            // Exemplo de implementação: https://github.com/MemedDev/example-integration-php/blob/master/src/js/main.js
            // initEventsMemed();
            console.log("Script Memed Carregado");
            setPaciente();
            blockAcaoUsario();
            salvaPrescricao();
            showPrescricao();
            
            $('#load').css('display', 'none');
        });

        document.getElementById("api_memed").appendChild(script);
    }

    function setPaciente(){
        MdSinapsePrescricao.event.add('core:moduleInit', (module) => {
            if (module.name === 'plataforma.prescricao') {
                /** 
                 * Todos os comandos e eventos da Memed devem ser executados 
                 * após a vefificação acima, pois garante que o script foi carregado.
                 */
                MdHub.command.send('plataforma.prescricao', 'setPaciente', {
                    external_id: "{{$paciente->id}}",
                    nome: "{{$paciente->nome}}",
                    cpf: "{{$paciente->cpf}}",
                    telefone: "{{$paciente->telefone1}}",
                    data_nascimento: "{{!empty($paciente->nascimento) ? date('d/m/Y', strtotime($paciente->nascimento)) : null}}",
                    endereco: "{{!empty($paciente->rua) ? $paciente->rua.', '.$paciente->numero : null}}",
                    cidade: "{{!empty($paciente->cidade) ? $paciente->cidade : null}}"
                });

                console.log("Paciente Carregado")
            }
        });
    }

    function blockAcaoUsario(){
        MdSinapsePrescricao.event.add('core:moduleInit', (module) => {
            if (module.name === 'plataforma.prescricao') {
                MdHub.command.send('plataforma.prescricao', 'setFeatureToggle', {
                    // Desativa a opção de excluir um paciente
                    deletePatient: false,
                    // Desabilita a opção de remover/trocar o paciente
                    removePatient: false,
                    // Esconde o formulário de edição do paciente
                    editPatient: false,
                    //esconde botão de close modulo
                    buttonClose: false,
                    //escode guia de primeiro acesso
                    guidesOnboarding: false,
                    //proibe remover prescrições salvas
                    removePrescription: false,
                    //Esconde botão de ajuda
                    showHelpMenu: false,
                    //esconte botão de vinculo usuario
                    //dropdownSync: false,

                });

                console.log("Ações de usuario bloqueadas")
            }
        });
    }

    function showPrescricao(){
        MdSinapsePrescricao.event.add('core:moduleInit', (module) => {
            if (module.name === 'plataforma.prescricao') {
                MdHub.module.show('plataforma.prescricao');
            }
        });
    }

    function salvaPrescricao(){
        MdSinapsePrescricao.event.add('core:moduleInit', (module) => {
            if (module.name === 'plataforma.prescricao') {
                MdHub.event.add('prescricaoImpressa', function(prescriptionData) {
                    console.log("Salvando prescrição");
                    console.log(prescriptionData);

                    paciente_id = $("#paciente_id").val();
                    agendamento_id = $("#agendamento_id").val();

                    $.ajax("{{ route('agendamento.receituario_memed.salvaReceituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id']) }}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id), {
                        method: "POST",
                        data: {
                            '_token': '{{csrf_token()}}',
                            'prescricao': prescriptionData
                        },

                        success: function (response) {

                            $.toast({
                                heading: response.title,
                                text: response.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: response.icon,
                                hideAfter: 3000,
                                stack: 10
                            });

                        },

                        error: function (response) {
                            if(response.responseJSON.errors){
                                Object.keys(response.responseJSON.errors).forEach(function(key) {
                                    $.toast({
                                        heading: 'Erro',
                                        text: response.responseJSON.errors[key][0],
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'error',
                                        hideAfter: 9000,
                                        stack: 10
                                    });

                                });
                            }
                        }
                    });
                    
                    // No objeto "prescriptionData" é retornado as informações da prescrição gerada.
                    // Implementar ações, callbacks, etc. para salvar informações da prescrição em banco
                });
            }
        });
    }    
</script>