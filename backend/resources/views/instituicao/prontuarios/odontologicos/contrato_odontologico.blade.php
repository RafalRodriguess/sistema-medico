
<body onload="window.print()">

    <p style="text-align: center;"><img src="{{\Storage::cloud()->url($instituicao->imagem)}}" style="height: 60px"></p>
    
    <p style="text-align: center;"><u>CONTRATO DE PRESTAÇÃO DE SERVIÇOS ODONTOLÓGICOS</u></p><br>
    
    <p style="text-align: justify;">CONTRATANTE: {{$paciente->nome}}, {{$idade}} anos, portador(a) da Carteira de Identidade nº {{$paciente->identidade}}, inscrito(a) no CPF/MF sob o nº {{$paciente->cpf}}, denominado(a) simplesmente PACIENTE. 
    CONTRATADA: {{$instituicao->razao_social}}, pessoa jurídica de direito privado, inscrita no CNPJ/MF sob o nº {{$instituicao->cnpj}}, sediada em {{$instituicao->cidade}}/{{$instituicao->estado}}, na {{$instituicao->rua}}, nº {{$instituicao->numero}}, bairro {{$instituicao->bairro}}, CEP {{$instituicao->cep}}, neste ato denominada simplesmente {{$instituicao->nome}}.
    Por este instrumento particular as partes acima qualificadas resolvem de comum acordo, celebrar o presente Contrato de Prestação de Serviços Odontológicos, nos termos que seguem adiante. </p>
    <p style="text-align: justify;"><b>Cláusula 1ª </b>- Pelo presente contrato a {{$instituicao->nome}} se compromete à prestação do(s) seguinte(s) serviço(s) odontológico(s) ANEXADOS NESTE CONTRATO.</p>
    <p>
        <ul>
        @foreach ($orcamento->itens as $item)
            <li style="list-style-type: none;">{{$item->procedimentos->procedimentoInstituicao->procedimento->descricao}}</li>
        @endforeach
        </ul>
    </p>
    <p style="text-align: justify;"><b>Cláusula 2ª</b> - Em contrapartida aos serviços acima descritos, o(a) PACIENTE pagará à  {{$instituicao->nome}} a importância líquida e total de <?php if (!empty($orcamento->desconto) && $orcamento->desconto > 0){ ?>
        <?php 
            // $valor_total_com_desconto = ($orcamento->valor_aprovado - $orcamento->desconto);
            // $valor_total_com_desconto = round($valor_total_com_desconto);
            echo number_format($orcamento->valor_aprovado - $orcamento->desconto, 2, ',', '');
        ?>
    <?php }else{ ?><?php echo $orcamento->valor_aprovado ?> <?php } ?> que será quitada da seguinte forma: <br></p>
    
    <p>
        <ul>
            @foreach ($contas as $item)
                <li style="list-style-type: none;">
                    {{App\ContaReceber::forma_pagamento_texto($item->forma_pagamento)}} 
                    @if ($item->qtd_parcelas > 1)
                        {{$item->qtd_parcelas}}x R$ {{number_format(($item->valor_total / $item->qtd_parcelas), 2, ',', '.')}}
                    @else
                        R$ {{number_format(($item->valor_total), 2, ',', '.')}}
                    @endif
                </li>
            @endforeach
        </ul>
    </p>
    
    <p style="text-align: justify;">§1º Nos tratamentos que envolva a prática de mais de um procedimento em dias diferentes, em caso de desistência do PACIENTE ou impossibilidade definitiva de realização de algum procedimento por problemas de saúde, ato ou omissão do PACIENTE, inclusive no cumprimento das medidas indicadas pelo profissional de saúde para sucesso do tratamento, será devido o valor correspondente aos procedimentos realizados e, ainda, uma multa de 40% sobre o valor dos procedimentos não realizados, a título de indenização pela reserva de agenda e aquisição de materiais, além de despesas bancárias e administrativas. O estorno deverá ser requerido por escrito pelo PACIENTE e será feito em até 10 dias após seja compensada a última parcela.</p>
    <p style="text-align: justify;">§ 2º Em caso de falecimento do PACIENTE antes da finalização do tratamento, poderão os herdeiros solicitar por escrito, mediante comprovação oficial de sua condição, o estorno dos valores relativos a procedimentos não realizados, a serem pagos em até 10 dias após o recebimento da última parcela. </p>  
    <p style="text-align: justify;">§ 3° No caso de tratamentos pagos, quando o (a) PACIENTE deixar de retornar a clínica por mais de trinta dias após o agendamento, sem qualquer comunicado ou motivo legal, o mesmo perderá o direito às importâncias pagas, não havendo devolução de qualquer valor. </p>  
    <p style="text-align: justify;"><b>Cláusula 3ª</b> - Se antes, durante e ao final do tratamento, outro(s) procedimento(s) e exame(s) paralelo(s) ou complementar(es) se fizer(em) necessário(s) conforme avaliação do cirurgião-dentista, e/ou solicitado(s) pelo(a) PACIENTE, esse(s) será(ão) objeto de aditamento ou de novo contrato a ser celebrado entre as partes e terá(ão) seu(s) custo(s) cobrado(s) separadamente do(a) PACIENTE, o(a) qual será prévia e expressamente informado(a) pela {{$instituicao->nome}} acerca do(s) seu(s) valor(es), prazo(s) e forma(s) de pagamento. </p>
    <p style="text-align: justify;"><b>Cláusula 4ª</b> - Neste ato o(a) PACIENTE e/ou seu representante legal declara, para todos os fins de direito, que recebeu da {{$instituicao->nome}}, de forma clara e objetiva, todas as informações relacionadas aos serviços ora contratados, especialmente no que se refere às opções/alternativas e técnicas de tratamento, procedimentos, cronograma, riscos e consequências, benefícios, materiais, exames, custos, obrigações do(a) PACIENTE, utilização da medicação pré e pós-operatória, possíveis desconfortos e edemas e condições da garantia contratual, tendo lhe sido dada a oportunidade para questionar e esclarecer todas as suas dúvidas acerca dos serviços objeto deste contrato.</p>
    <p style="text-align: justify;"><b>Cláusula 5ª</b> - Também neste ato o(a) PACIENTE e/ou seu representante legal declara ciência quanto à ausência de qualquer responsabilidade da {{$instituicao->nome}}, e/ou do(a) cirurgião(ã)-dentista responsável pelo procedimento e/ou tratamento odontológico, quanto a quaisquer prejuízos e danos, sejam eles materiais, estéticos ou morais, decorrentes da sua não cooperação durante e após o tratamento, ou ainda pela omissão de informações sobre sua saúde e/ou que sejam relevantes para o diagnóstico e tratamento do caso.</p>
    <p style="text-align: justify;">Entende-se como não cooperação do(a) PACIENTE o não comparecimento às consultas, exames e procedimentos odontológicos, o abandono do tratamento e a não observação das orientações e recomendações que lhe forem prescritas. </p>
    <p style="text-align: justify;"><b>Cláusula 6ª</b> - O(A) PACIENTE e/ou seu representante legal declara-se ciente de que o abandono ou desistência do tratamento odontológico já iniciado poderá acarretar prejuízos à sua saúde e estética, inclusive com agravamento do estado inicial, e de que a {{$instituicao->nome}} estará isenta de qualquer responsabilidade pelos danos oriundos do seu abandono ou desistência. </p>
    <p style="text-align: justify;"><b>Cláusula 7ª</b> - O presente contrato constitui a integralidade do que foi acordado entre as partes, substituindo quaisquer entendimentos ou acordos anteriores à sua assinatura. </p>
    <p style="text-align: justify;"><b>Cláusula 8ª- O (A) PACIENTE e/ ou seu representante legal deverá estar RIGOROSAMENTE em dia com o Plano de Assistência Familiar PAX MINAS AVELAR durante o tratamento odontológico, item imprescindível para iniciação, continuação e término do tratamento relacionado neste termo.</b> </p>
    <p style="text-align: justify;"><b>Cláusula 9ª</b> - Fica eleito o foro da Comarca de Montes Claros/MG para dirimir quaisquer dúvidas relativas ao presente contrato e seus documentos complementares, renunciando expressamente a qualquer outro por mais privilegiado que seja. </p>
    <p style="text-align: justify;"><b>Cláusula 10ª</b> Todos os procedimentos odontológicos executados por força do contrato terão garantia de 01 (um) ano, contados da data de conclusão do procedimento, desde que o(a) paciente cumpra rigorosamente suas obrigações contratuais e todas as orientações e recomendações. A garantia prevista se limita exclusivamente à correção técnica e científica de todos os procedimentos realizados, mesmo sabendo que cada indivíduo responde de forma única e específica ao procedimento, existindo, em toda e qualquer intervenção, a possibilidade de ocorrência de resultados não satisfatórios, sem que isso represente negligência, imprudência ou imperícia por parte da clínica e/ou do cirurgião(â)-dentista responsável pelo procedimento. Procedimentos como prótese dentária, podem necessitar de reembasamento a cada 6 (seis) meses, sendo a cargo do paciente as despesas. Para validade da garantia prevista o (a) paciente deverá, obrigatoriamente, concluir todas as etapas do tratamento proposto e retornar a cada 6 (seis) meses para fins de avaliação. O não retorno do(a) PACIENTE em qualquer dos prazos previstos, salvo a prévia comunicação e justificativa no prazo mínimo de 24 (vinte e quatro) horas, implicará o cancelamento automático da garantia contratual mencionada nas cláusulas.</p>
    <p style="text-align: justify;">E assim, por estarem justas e contratadas, as partes assinam o presente instrumento particular de Contrato de Prestação de Serviços Odontológicos em 2 (duas) vias de igual forma e teor. </p>
    
    <p>Montes Claros/MG, <?php echo date('d') ?> de <?php setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Fortaleza');
    echo strftime('%B', strtotime('today')); ?> de <?php echo date('Y') ?>.</p>
    
    _________________________________ <br> <br>
    Paciente: {{$paciente->nome}} <br> <br>
    
    _________________________________ <br> <br>
    Representante legal do Paciente <br> <br>
    Nome: <br> <br>
    RG: <br> <br>
    CPF: <br> <br>
    _________________________________ <br> <br>
    {{$instituicao->nome}} <br> <br>
    
    
    Testemunhas: <br> <br>
    
    1.____________________________________<br> <br>
    Nome: <br> <br>
    CPF: <br> <br>
    
    2.____________________________________ <br> <br>
    Nome: <br> <br>
    CPF: <br> <br>
    
    </body>
    