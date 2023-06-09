<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class ConfiguracaoFiscal extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'configuracoes_fiscais';

    protected $fillable = [
        'id',
        'descricao',
        'instituicao_id',
        'aliquota_iss',
        'iss_retido_fonte',
        'cnae',
        'cod_servico_municipal',
        'p_pis',
        'p_cofins',
        'p_inss',
        'p_ir',
        'regime',
        'item_lista_servicos',
        'usuario',
        'senha',
        'certificado',
        'senha_certificado',
        'ambiente',
        'regime_especial_tributacao'
    ];

    //Regime
    const mei = "mei";
    const simples_nacional = 'simples_nacional';
    const lucro_real = 'lucro_real';
    const lucro_presumido = 'lucro_presumido';

    public static function regime()
    {
        return [
            self::mei,
            self::simples_nacional,
            self::lucro_real,
            self::lucro_presumido,
        ];
    }

    public static function regime_texto($regime = null)
    {
        $data = [
            self::mei => 'MEI',
            self::simples_nacional => 'Simples Nacional',
            self::lucro_real => 'Lucro Real',
            self::lucro_presumido => 'Lucro Presumido',
        ];

        if($regime == null){
            return $data;
        }else{
            return $data[$regime];
        }
    }

    public static function getListaServicos(){
        $lista = [   
            [
                'codigo' => '1',
                'grupo' => 'Serviços de informática e congêneres.',
                'servicos' => [
                    [
                        'codigo' => '1.01',
                        'descricao' => "Análise e desenvolvimento de sistemas."
                    ],
                    [
                        'codigo' => '1.02',
                        'descricao' => "Programação"
                    ],
                    [
                        'codigo' => '1.03',
                        'descricao' => "Processamento de dados e congêneres."
                    ],
                    [
                        'codigo' => '1.04',
                        'descricao' => "Elaboração de programas de computadores, inclusive de jogos eletrônicos."
                    ],
                    [
                        'codigo' => '1.05',
                        'descricao' => "Licenciamento ou cessão de direito de uso de programas de computação."
                    ],
                    [
                        'codigo' => '1.06',
                        'descricao' => "Assessoria e consultoria em informática."
                    ],
                    [
                        'codigo' => '1.07',
                        'descricao' => "Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados."
                    ],
                    [
                        'codigo' => '1.08',
                        'descricao' => "Planejamento, confecção, manutenção e atualização de páginas eletrônicas."
                    ],
                ]
            ],
            [
                'codigo' => '2',
                'grupo' => 'Serviços de pesquisas e desenvolvimento de qualquer natureza.',
                'servicos' => [
                    [
                        'codigo' => '2.01',
                        'descricao' => 'Serviços de pesquisas e desenvolvimento de qualquer natureza.'
                    ],
                ]
            ],
            [
                'codigo' => '3',
                'grupo' => 'Serviços prestados mediante locação, cessão de direito de uso e congêneres.',
                'servicos' => [
                    [
                        'codigo' => '3.01',
                        'descricao' => '(VETADO) 3.02 - Cessão de direito de uso de marcas e de sinais de propaganda.'
                    ],
                    [
                        'codigo' => '3.03',
                        'descricao' => 'Exploração de salões de festas, centro de convenções, escritórios virtuais, stands, quadras esportivas, estádios, ginásios, auditórios, casas de espetáculos, parques de diversões, canchas e congêneres, para realização de eventos ou negócios de qualquer natureza.'
                    ],
                    [
                        'codigo' => '3.04',
                        'descricao' => 'Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não, de ferrovia, rodovia, postes, cabos, dutos e condutos de qualquer natureza.'
                    ],
                    [
                        'codigo' => '3.05',
                        'descricao' => 'Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário.'
                    ],
                ]
            ],
            [
                'codigo' => '4',
                'grupo' => 'Serviços de saúde, assistência médica e congêneres.',
                'servicos' => [
                    [
                        'codigo' => '4.01',
                        'descricao' => 'Medicina e biomedicina.'
                    ],
                    [
                        'codigo' => '4.02',
                        'descricao' => 'Análises clínicas, patologia, eletricidade médica, radioterapia, quimioterapia, ultra-sonografia, ressonância magnética, radiologia, tomografia e congêneres.'],
                    [
                        'codigo' => '4.03',
                        'descricao' => 'Hospitais, clínicas, laboratórios, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.'
                    ],
                    [
                        'codigo' => '4.04',
                        'descricao' => 'Instrumentação cirúrgica.'
                    ],
                    [
                        'codigo' => '4.05',
                        'descricao' => 'Acupuntura.'
                    ],
                    [
                        'codigo' => '4.06',
                        'descricao' => 'Enfermagem, inclusive serviços auxiliares.'
                    ],
                    [
                        'codigo' => '4.07',
                        'descricao' => 'Serviços farmacêuticos.'
                    ],
                    [
                        'codigo' => '4.08',
                        'descricao' => 'Terapia ocupacional, fisioterapia e fonoaudiologia.'
                    ],
                    [
                        'codigo' => '4.09',
                        'descricao' => 'Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental.'
                    ],
                    [
                        'codigo' => '4.10',
                        'descricao' => 'Nutrição.'
                    ],
                    [
                        'codigo' => '4.11',
                        'descricao' => 'Obstetrícia.'
                    ],
                    [
                        'codigo' => '4.12',
                        'descricao' => 'Odontologia.'
                    ],
                    [
                        'codigo' => '4.13',
                        'descricao' => 'Ortóptica.'
                    ],
                    [
                        'codigo' => '4.14',
                        'descricao' => 'Próteses sob encomenda.'
                    ],
                    [
                        'codigo' => '4.15',
                        'descricao' => 'Psicanálise.'
                    ],
                    [
                        'codigo' => '4.16',
                        'descricao' => 'Psicologia.'
                    ],
                    [
                        'codigo' => '4.17',
                        'descricao' => 'Casas de repouso e de recuperação, creches, asilos e congêneres.'
                    ],
                    [
                        'codigo' => '4.18',
                        'descricao' => 'Inseminação artificial, fertilização in vitro e congêneres.'
                    ],
                    [
                        'codigo' => '4.19',
                        'descricao' => 'Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres.'
                    ],
                    [
                        'codigo' => '4.20',
                        'descricao' => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.'
                    ],
                    [
                        'codigo' => '4.21',
                        'descricao' => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres.'
                    ],
                    [
                        'codigo' => '4.22',
                        'descricao' => 'Planos de medicina de grupo ou individual e convênios para prestação de assistência médica, hospitalar, odontológica e congêneres.'
                    ],
                    [
                        'codigo' => '4.23',
                        'descricao' => 'Outros planos de saúde que se cumpram através de serviços de terceiros contratados, credenciados, cooperados ou apenas pagos pelo operador do plano mediante indicação do beneficiário.'
                    ],
                ]
            ],
            [
                'codigo' => '5',
                'grupo' => 'Serviços de medicina e assistência veterinária e congêneres.',
                'servicos' => [
                    [
                        'codigo' => '5.01',
                        'descricao' => 'Medicina veterinária e zootecnia.'
                    ],
                    [
                        'codigo' => '5.02',
                        'descricao' => 'Hospitais, clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária.'
                    ],
                    [
                        'codigo' => '5.03',
                        'descricao' => 'Laboratórios de análise na área veterinária.'
                    ],
                    [
                        'codigo' => '5.04',
                        'descricao' => 'Inseminação artificial, fertilização in vitro e congêneres.'
                    ],
                    [
                        'codigo' => '5.05',
                        'descricao' => 'Bancos de sangue e de órgãos e congêneres.'],
                    [
                        'codigo' => '5.06',
                        'descricao' => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.'
                    ],
                    [
                        'codigo' => '5.07',
                        'descricao' => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres.'
                    ],
                    [
                        'codigo' => '5.08',
                        'descricao' => 'Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres.'
                    ],
                    [
                        'codigo' => '5.09',
                        'descricao' => 'Planos de atendimento e assistência médico-veterinária.'
                    ],
                ]
            ],
            [
                'codigo' => '6',
                'grupo' => 'Serviços de cuidados pessoais, estética, atividades físicas e congêneres.',
                'servicos' =>[
                    ['codigo' => '6.01', 'descricao' => 'Barbearia, cabeleireiros, manicuros, pedicuros e congêneres.'],
                    ['codigo' => '6.02', 'descricao' => 'Esteticistas, tratamento de pele, depilação e congêneres.'],
                    ['codigo' => '6.03', 'descricao' => 'Banhos, duchas, sauna, massagens e congêneres.'],
                    ['codigo' => '6.04', 'descricao' => 'Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas.'],
                    ['codigo' => '6.05', 'descricao' => 'Centros de emagrecimento, spa e congêneres.'],
                ]
            ],           
            [
                'codigo' => '7',
                'grupo' => 'Serviços relativos a engenharia, arquitetura, geologia, urbanismo, construção civil, manutenção, limpeza, meio ambiente, saneamento e congêneres.',
                'servicos' => [
                    ['codigo' => '7.01', 'descricao' => 'Engenharia, agronomia, agrimensura, arquitetura, geologia, urbanismo, paisagismo e congêneres.'],
                    ['codigo' => '7.02', 'descricao' => 'Execução, por administração, empreitada ou subempreitada, de obras de construção civil, hidráulica ou elétrica e de outras obras semelhantes, inclusive sondagem, perfuração de poços, escavação, drenagem e irrigação, terraplanagem, pavimentação, concretagem e a instalação e montagem de produtos, peças e equipamentos (exceto o fornecimento de mercadorias produzidas pelo prestador de serviços fora do local da prestação dos serviços, que fica sujeito ao ICMS).'],
                    ['codigo' => '7.03', 'descricao' => 'Elaboração de planos diretores, estudos de viabilidade, estudos organizacionais e outros, relacionados com obras e serviços de engenharia; elaboração de anteprojetos, projetos básicos e projetos executivos para trabalhos de engenharia.'],
                    ['codigo' => '7.04', 'descricao' => 'Demolição.'],
                    ['codigo' => '7.05', 'descricao' => 'Reparação, conservação e reforma de edifícios, estradas, pontes, portos e congêneres (exceto o fornecimento de mercadorias produzidas pelo prestador dos serviços, fora do local da prestação dos serviços, que fica sujeito ao ICMS).'],
                    ['codigo' => '7.06', 'descricao' => 'Colocação e instalação de tapetes, carpetes, assoalhos, cortinas, revestimentos de parede, vidros, divisórias, placas de gesso e congêneres, com material fornecido pelo tomador do serviço.'],
                    ['codigo' => '7.07', 'descricao' => 'Recuperação, raspagem, polimento e lustração de pisos e congêneres.'],
                    ['codigo' => '7.08', 'descricao' => 'Calafetação.'],
                    ['codigo' => '7.09', 'descricao' => 'Varrição, coleta, remoção, incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resíduos quaisquer.'],
                    ['codigo' => '7.10', 'descricao' => 'Limpeza, manutenção e conservação de vias e logradouros públicos, imóveis, chaminés, piscinas, parques, jardins e congêneres.'],
                    ['codigo' => '7.11', 'descricao' => 'Decoração e jardinagem, inclusive corte e poda de árvores.'],
                    ['codigo' => '7.12', 'descricao' => 'Controle e tratamento de efluentes de qualquer natureza e de agentes físicos, químicos e biológicos.'],
                    ['codigo' => '7.13', 'descricao' => 'Dedetização, desinfecção, desinsetização, imunização, higienização, desratização, pulverização e congêneres.'],
                    ['codigo' => '7.14', 'descricao' => '(VETADO) 7.15-(VETADO) 7.16-Florestamento, reflorestamento, semeadura, adubação e congêneres.'],
                    ['codigo' => '7.17', 'descricao' => 'Escoramento, contenção de encostas e serviços congêneres.'],
                    ['codigo' => '7.18', 'descricao' => 'Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas, represas, açudes e congêneres.'],
                    ['codigo' => '7.19', 'descricao' => 'Acompanhamento e fiscalização da execução de obras de engenharia, arquitetura e urbanismo.'],
                    ['codigo' => '7.20', 'descricao' => 'Aerofotogrametria (inclusive interpretação), cartografia, mapeamento, levantamentos topográficos, batimétricos, geográficos, geodésicos, geológicos, geofísicos e congêneres.'],
                    ['codigo' => '7.21', 'descricao' => 'Pesquisa, perfuração, cimentação, mergulho, perfilagem, concretação, testemunhagem, pescaria, estimulação e outros serviços relacionados com a exploração e explotação de petróleo, gás natural e de outros recursos minerais.'],
                    ['codigo' => '7.22', 'descricao' => 'Nucleação e bombardeamento de nuvens e congêneres.'],
                    
                ]
            ],
            [
                'codigo' => '8',
                'grupo' => 'Serviços de educação, ensino, orientação pedagógica e educacional, instrução, treinamento e avaliação pessoal de qualquer grau ou natureza.',
                'servicos' => [
                    ['codigo' => '8.01', 'descricao' => 'Ensino regular pré-escolar, fundamental, médio e superior.'],
                    ['codigo' => '8.02', 'descricao' => 'Instrução, treinamento, orientação pedagógica e educacional, avaliação de conhecimentos de qualquer natureza.'],
                ]
            ],            
            [
                'codigo' => '9',
                'grupo' => 'Serviços relativos a hospedagem, turismo, viagens e congêneres.',
                'servicos' => [
                    ['codigo' => '9.01', 'descricao' => 'Hospedagem de qualquer natureza em hotéis, apart-service condominiais, flat, apart-hotéis, hotéis residência, residence-service, suite service, hotelaria marítima, motéis, pensões e congêneres; ocupação por temporada com fornecimento de serviço (o valor da alimentação e gorjeta, quando incluído no preço da diária, fica sujeito ao Imposto Sobre Serviços).'],
                    ['codigo' => '9.02', 'descricao' => 'Agenciamento, organização, promoção, intermediação e execução de programas de turismo, passeios, viagens, excursões, hospedagens e congêneres.'],
                    ['codigo' => '9.03', 'descricao' => 'Guias de turismo.'],
                ]
            ],
            [
                'codigo' => '10',
                'grupo' => 'Serviços de intermediação e congêneres.',
                'servicos' => [
                    ['codigo' => '10.01', 'descricao' => 'Agenciamento, corretagem ou intermediação de câmbio, de seguros, de cartões de crédito, de planos de saúde e de planos de previdência privada.'],
                    ['codigo' => '10.02', 'descricao' => 'Agenciamento, corretagem ou intermediação de títulos em geral, valores mobiliários e contratos quaisquer.'],
                    ['codigo' => '10.03', 'descricao' => 'Agenciamento, corretagem ou intermediação de direitos de propriedade industrial, artística ou literária.'],
                    ['codigo' => '10.04', 'descricao' => 'Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil (leasing), de franquia (franchising) e de faturização (factoring).'],
                    ['codigo' => '10.05', 'descricao' => 'Agenciamento, corretagem ou intermediação de bens móveis ou imóveis, não abrangidos em outros itens ou subitens, inclusive aqueles realizados no âmbito de Bolsas de Mercadorias e Futuros, por quaisquer meios.'],
                    ['codigo' => '10.06', 'descricao' => 'Agenciamento marítimo.'],
                    ['codigo' => '10.07', 'descricao' => 'Agenciamento de notícias.'],
                    ['codigo' => '10.08', 'descricao' => 'Agenciamento de publicidade e propaganda, inclusive o agenciamento de veiculação por quaisquer meios.'],
                    ['codigo' => '10.09', 'descricao' => 'Representação de qualquer natureza, inclusive comercial.'],
                    ['codigo' => '10.10', 'descricao' => 'Distribuição de bens de terceiros.'],
                ]
            ],
            [
                'codigo' => '11',
                'grupo' => 'Serviços de guarda, estacionamento, armazenamento, vigilância e congêneres.',
                'servicos' => [
                    ['codigo' => '11.01', 'descricao' => 'Guarda e estacionamento de veículos terrestres automotores, de aeronaves e de embarcações.'],
                    ['codigo' => '11.02', 'descricao' => 'Vigilância, segurança ou monitoramento de bens e pessoas.'],
                    ['codigo' => '11.03', 'descricao' => 'Escolta, inclusive de veículos e cargas.'],
                    ['codigo' => '11.04', 'descricao' => 'Armazenamento, depósito, carga, descarga, arrumação e guarda de bens de qualquer espécie.'],
                ]
            ],            
            [
                'codigo' => '12',
                'grupo' => 'Serviços de diversões, lazer, entretenimento e congêneres.',
                'servicos' => [
                    ['codigo' => '12.01', 'descricao' => 'Espetáculos teatrais.'],
                    ['codigo' => '12.02', 'descricao' => 'Exibições cinematográficas.'],
                    ['codigo' => '12.03', 'descricao' => 'Espetáculos circenses.'],
                    ['codigo' => '12.04', 'descricao' => 'Programas de auditório.'],
                    ['codigo' => '12.05', 'descricao' => 'Parques de diversões, centros de lazer e congêneres.'],
                    ['codigo' => '12.06', 'descricao' => 'Boates, taxi-dancing e congêneres.'],
                    ['codigo' => '12.07', 'descricao' => 'Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais, festivais e congêneres.'],
                    ['codigo' => '12.08', 'descricao' => 'Feiras, exposições, congressos e congêneres.'],
                    ['codigo' => '12.09', 'descricao' => 'Bilhares, boliches e diversões eletrônicas ou não.'],
                    ['codigo' => '12.10', 'descricao' => 'Corridas e competições de animais.'],
                    ['codigo' => '12.11', 'descricao' => 'Competições esportivas ou de destreza física ou intelectual, com ou sem a participação do espectador.'],
                    ['codigo' => '12.12', 'descricao' => 'Execução de música.'],
                    ['codigo' => '12.13', 'descricao' => 'Produção, mediante ou sem encomenda prévia, de eventos, espetáculos, entrevistas, shows, ballet, danças, desfiles, bailes, teatros, óperas, concertos, recitais, festivais e congêneres.'],
                    ['codigo' => '12.14', 'descricao' => 'Fornecimento de música para ambientes fechados ou não, mediante transmissão por qualquer processo.'],
                    ['codigo' => '12.15', 'descricao' => 'Desfiles de blocos carnavalescos ou folclóricos, trios elétricos e congêneres.'],
                    ['codigo' => '12.16', 'descricao' => 'Exibição de filmes, entrevistas, musicais, espetáculos, shows, concertos, desfiles, óperas, competições esportivas, de destreza intelectual ou congêneres.'],
                    ['codigo' => '12.17', 'descricao' => 'Recreação e animação, inclusive em festas e eventos de qualquer natureza.'],
                ]            
            ],
            [
                'codigo' => '13',
                'grupo' => 'Serviços relativos a fonografia, fotografia, cinematografia e reprografia.',
                'servicos' => [
                    ['codigo' => '13.01', 'descricao' => '(VETADO) 13.02-Fonografia ou gravação de sons, inclusive trucagem, dublagem, mixagem e congêneres.'],
                    ['codigo' => '13.03', 'descricao' => 'Fotografia e cinematografia, inclusive revelação, ampliação, cópia, reprodução, trucagem e congêneres.'],
                    ['codigo' => '13.04', 'descricao' => 'Reprografia, microfilmagem e digitalização.'],
                    ['codigo' => '13.05', 'descricao' => 'Composição gráfica, fotocomposição, clicheria, zincografia, litografia, fotolitografia.'],
                ]
            ],
            
            [
                'codigo' => '14',
                'grupo' => 'Serviços relativos a bens de terceiros.',
                'servicos' => [
                    ['codigo' => '14.01', 'descricao' => 'Lubrificação, limpeza, lustração, revisão, carga e recarga, conserto, restauração, blindagem, manutenção e conservação de máquinas, veículos, aparelhos, equipamentos, motores, elevadores ou de qualquer objeto (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).'],
                    ['codigo' => '14.02', 'descricao' => 'Assistência técnica.'],
                    ['codigo' => '14.03', 'descricao' => 'Recondicionamento de motores (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).'],
                    ['codigo' => '14.04', 'descricao' => 'Recauchutagem ou regeneração de pneus.'],
                    ['codigo' => '14.05', 'descricao' => 'Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodização, corte, recorte, polimento, plastificação e congêneres, de objetos quaisquer.'],
                    ['codigo' => '14.06', 'descricao' => 'Instalação e montagem de aparelhos, máquinas e equipamentos, inclusive montagem industrial, prestados ao usuário final, exclusivamente com material por ele fornecido.'],
                    ['codigo' => '14.07', 'descricao' => 'Colocação de molduras e congêneres.'],
                    ['codigo' => '14.08', 'descricao' => 'Encadernação, gravação e douração de livros, revistas e congêneres.'],
                    ['codigo' => '14.09', 'descricao' => 'Alfaiataria e costura, quando o material for fornecido pelo usuário final, exceto aviamento.'],
                    ['codigo' => '14.10', 'descricao' => 'Tinturaria e lavanderia.'],
                    ['codigo' => '14.11', 'descricao' => 'Tapeçaria e reforma de estofamentos em geral.'],
                    ['codigo' => '14.12', 'descricao' => 'Funilaria e lanternagem.'],
                    ['codigo' => '14.13', 'descricao' => 'Carpintaria e serralheria.'],
                ]
            ],            
            [
                'codigo' => '15',
                'grupo' => 'Serviços relacionados ao setor bancário ou financeiro, inclusive aqueles prestados por instituições financeiras autorizadas a funcionar pela União ou por quem de direito.',
                'servicos' => [
                    ['codigo' => '15.01', 'descricao' => 'Administração de fundos quaisquer, de consórcio, de cartão de crédito ou débito e congêneres, de carteira de clientes, de cheques pré-datados e congêneres.'],
                    ['codigo' => '15.02', 'descricao' => 'Abertura de contas em geral, inclusive conta-corrente, conta de investimentos e aplicação e caderneta de poupança, no País e no exterior, bem como a manutenção das referidas contas ativas e inativas.'],
                    ['codigo' => '15.03', 'descricao' => 'Locação e manutenção de cofres particulares, de terminais eletrônicos, de terminais de atendimento e de bens e equipamentos em geral.'],
                    ['codigo' => '15.04', 'descricao' => 'Fornecimento ou emissão de atestados em geral, inclusive atestado de idoneidade, atestado de capacidade financeira e congêneres.'],
                    ['codigo' => '15.05', 'descricao' => 'Cadastro, elaboração de ficha cadastral, renovação cadastral e congêneres, inclusão ou exclusão no Cadastro de Emitentes de Cheques sem Fundos-CCF ou em quaisquer outros bancos cadastrais.'],
                    ['codigo' => '15.06', 'descricao' => 'Emissão, reemissão e fornecimento de avisos, comprovantes e documentos em geral; abono de firmas; coleta e entrega de documentos, bens e valores; comunicação com outra agência ou com a administração central; licenciamento eletrônico de veículos; transferência de veículos; agenciamento fiduciário ou depositário; devolução de bens em custódia.'],
                    ['codigo' => '15.07', 'descricao' => 'Acesso, movimentação, atendimento e consulta a contas em geral, por qualquer meio ou processo, inclusive por telefone, fac-símile, internet e telex, acesso a terminais de atendimento, inclusive vinte e quatro horas; acesso a outro banco e a rede compartilhada; fornecimento de saldo, extrato e demais informações relativas a contas em geral, por qualquer meio ou processo.'],
                    ['codigo' => '15.08', 'descricao' => 'Emissão, reemissão, alteração, cessão, substituição, cancelamento e registro de contrato de crédito; estudo, análise e avaliação de operações de crédito; emissão, concessão, alteração ou contratação de aval, fiança, anuência e congêneres; serviços relativos a abertura de crédito, para quaisquer fins.'],
                    ['codigo' => '15.09', 'descricao' => 'Arrendamento mercantil (leasing) de quaisquer bens, inclusive cessão de direitos e obrigações, substituição de garantia, alteração, cancelamento e registro de contrato, e demais serviços relacionados ao arrendamento mercantil (leasing).'],
                    ['codigo' => '15.10', 'descricao' => 'Serviços relacionados a cobranças, recebimentos ou pagamentos em geral, de títulos quaisquer, de contas ou carnês, de câmbio, de tributos e por conta de terceiros, inclusive os efetuados por meio eletrônico, automático ou por máquinas de atendimento; fornecimento de posição de cobrança, recebimento ou pagamento; emissão de carnês, fichas de compensação, impressos e documentos em geral.'],
                    ['codigo' => '15.11', 'descricao' => 'Devolução de títulos, protesto de títulos, sustação de protesto, manutenção de títulos, reapresentação de títulos, e demais serviços a eles relacionados.'],
                    ['codigo' => '15.12', 'descricao' => 'Custódia em geral, inclusive de títulos e valores mobiliários.'],
                    ['codigo' => '15.13', 'descricao' => 'Serviços relacionados a operações de câmbio em geral, edição, alteração, prorrogação, cancelamento e baixa de contrato de câmbio; emissão de registro de exportação ou de crédito; cobrança ou depósito no exterior; emissão, fornecimento e cancelamento de cheques de viagem; fornecimento, transferência, cancelamento e demais serviços relativos a carta de crédito de importação, exportação e garantias recebidas; envio e recebimento de mensagens em geral relacionadas a operações de câmbio.'],
                    ['codigo' => '15.14', 'descricao' => 'Fornecimento, emissão, reemissão, renovação e manutenção de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.'],
                    ['codigo' => '15.15', 'descricao' => 'Compensação de cheques e títulos quaisquer; serviços relacionados a depósito, inclusive depósito identificado, a saque de contas quaisquer, por qualquer meio ou processo, inclusive em terminais eletrônicos e de atendimento.'],
                    ['codigo' => '15.16', 'descricao' => 'Emissão, reemissão, liquidação, alteração, cancelamento e baixa de ordens de pagamento, ordens de crédito e similares, por qualquer meio ou processo; serviços relacionados à transferência de valores, dados, fundos, pagamentos e similares, inclusive entre contas em geral.'],
                    ['codigo' => '15.17', 'descricao' => 'Emissão, fornecimento, devolução, sustação, cancelamento e oposição de cheques quaisquer, avulso ou por talão.'],
                    ['codigo' => '15.18', 'descricao' => 'Serviços relacionados a crédito imobiliário, avaliação e vistoria de imóvel ou obra, análise técnica e jurídica, emissão, reemissão, alteração, transferência e renegociação de contrato, emissão e reemissão do termo de quitação e demais serviços relacionados a crédito imobiliário.'],        
                ]
            ],
            [
                'codigo' => '16',
                'grupo' => 'Serviços de transporte de natureza municipal.',
                'servicos' => [
                    ['codigo' => '16.01', 'descricao' => 'Serviços de transporte de natureza municipal.'],
                ]
            ],            
            [
                'codigo' => '17',
                'grupo' => 'Serviços de apoio técnico, administrativo, jurídico, contábil, comercial e congêneres.',
                'servicos' => [
                    ['codigo' => '17.01', 'descricao' => 'Assessoria ou consultoria de qualquer natureza, não contida em outros itens desta lista; análise, exame, pesquisa, coleta, compilação e fornecimento de dados e informações de qualquer natureza, inclusive cadastro e similares.'],
                    ['codigo' => '17.02', 'descricao' => 'Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.'],
                    ['codigo' => '17.03', 'descricao' => 'Planejamento, coordenação, programação ou organização técnica, financeira ou administrativa.'],
                    ['codigo' => '17.04', 'descricao' => 'Recrutamento, agenciamento, seleção e colocação de mão-de-obra.'],
                    ['codigo' => '17.05', 'descricao' => 'Fornecimento de mão-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadores, avulsos ou temporários, contratados pelo prestador de serviço.-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadores, avulsos ou temporários, contratados pelo prestador de serviço.'],
                    ['codigo' => '17.06', 'descricao' => 'Propaganda e publicidade, inclusive promoção de vendas, planejamento de campanhas ou sistemas de publicidade, elaboração de desenhos, textos e demais materiais publicitários.'],
                    ['codigo' => '17.07', 'descricao' => '(VETADO) 17.08-Franquia (franchising).'],
                    ['codigo' => '17.09', 'descricao' => 'Perícias, laudos, exames técnicos e análises técnicas.'],
                    ['codigo' => '17.10', 'descricao' => 'Planejamento, organização e administração de feiras, exposições, congressos e congêneres.'],
                    ['codigo' => '17.11', 'descricao' => 'Organização de festas e recepções; bufê (exceto o fornecimento de alimentação e bebidas, que fica sujeito ao ICMS).'],
                    ['codigo' => '17.12', 'descricao' => 'Administração em geral, inclusive de bens e negócios de terceiros.'],
                    ['codigo' => '17.13', 'descricao' => 'Leilão e congêneres.'],
                    ['codigo' => '17.14', 'descricao' => 'Advocacia.'],
                    ['codigo' => '17.15', 'descricao' => 'Arbitragem de qualquer espécie, inclusive jurídica.'],
                    ['codigo' => '17.16', 'descricao' => 'Auditoria.'],
                    ['codigo' => '17.17', 'descricao' => 'Análise de Organização e Métodos.'],
                    ['codigo' => '17.18', 'descricao' => 'Atuária e cálculos técnicos de qualquer natureza.'],
                    ['codigo' => '17.19', 'descricao' => 'Contabilidade, inclusive serviços técnicos e auxiliares.'],
                    ['codigo' => '17.20', 'descricao' => 'Consultoria e assessoria econômica ou financeira.'],
                    ['codigo' => '17.21', 'descricao' => 'Estatística.'],
                    ['codigo' => '17.22', 'descricao' => 'Cobrança em geral.'],
                    ['codigo' => '17.23', 'descricao' => 'Assessoria, análise, avaliação, atendimento, consulta, cadastro, seleção, gerenciamento de informações, administração de contas a receber ou a pagar e em geral, relacionados a operações de faturização (factoring).'],
                    ['codigo' => '17.24', 'descricao' => 'Apresentação de palestras, conferências, seminários e congêneres.'],
                    
                ] 
            ],
            [
                'codigo' => '18',
                'grupo' => 'Serviços de regulação de sinistros vinculados a contratos de seguros; inspeção e avaliação de riscos para cobertura de contratos de seguros; prevenção e gerência de riscos seguráveis e congêneres.',
                'servicos' => [
                    ['codigo' => '18.01', 'descricao' => 'Serviços de regulação de sinistros vinculados a contratos de seguros; inspeção e avaliação de riscos para cobertura de contratos de seguros; prevenção e gerência de riscos seguráveis e congêneres.'],
                ]
            ],
            
            [
                'codigo' => '19',
                'grupo' => 'Serviços de distribuição e venda de bilhetes e demais produtos de loteria, bingos, cartões, pules ou cupons de apostas, sorteios, prêmios, inclusive os decorrentes de títulos de capitalização e congêneres.',
                'servicos' => [
                    ['codigo' => '19.01', 'descricao' => 'Serviços de distribuição e venda de bilhetes e demais produtos de loteria, bingos, cartões, pules ou cupons de apostas, sorteios, prêmios, inclusive os decorrentes de títulos de capitalização e congêneres.'],
                ]
            ],
            [
                'codigo' => '20',
                'grupo' => 'Serviços portuários, aeroportuários, ferroportuários, de terminais rodoviários, ferroviários e metroviários.',
                'servicos' => [
                    ['codigo' => '20.01', 'descricao' => 'Serviços portuários, ferroportuários, utilização de porto, movimentação de passageiros, reboque de embarcações, rebocador escoteiro, atracação, desatracação, serviços de praticagem, capatazia, armazenagem de qualquer natureza, serviços acessórios, movimentação de mercadorias, serviços de apoio marítimo, de movimentação ao largo, serviços de armadores, estiva, conferência, logística e congêneres.'],
                    ['codigo' => '20.02', 'descricao' => 'Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros, armazenagem de qualquer natureza, capatazia, movimentação de aeronaves, serviços de apoio aeroportuários, serviços acessórios, movimentação de mercadorias, logística e congêneres.'],
                    ['codigo' => '20.03', 'descricao' => 'Serviços de terminais rodoviários, ferroviários, metroviários, movimentação de passageiros, mercadorias, inclusive suas operações, logística e congêneres.'],
                ]
            ],            
            [
                'codigo' => '21',
                'grupo' => 'Serviços de registros públicos, cartorários e notariais.',
                'servicos' => [
                    ['codigo' => '21.01', 'descricao' => 'Serviços de registros públicos, cartorários e notariais.'],
                ]
            ],
            [
                'codigo' => '22',
                'grupo' => 'Serviços de exploração de rodovia.',
                'servicos' => [
                    ['codigo' => '22.01', 'descricao' => 'Serviços de exploração de rodovia mediante cobrança de preço ou pedágio dos usuários, envolvendo execução de serviços de conservação, manutenção, melhoramentos para adequação de capacidade e segurança de trânsito, operação, monitoração, assistência aos usuários e outros serviços definidos em contratos, atos de concessão ou de permissão ou em normas oficiais.'],
                ]
            ],
            [
                'codigo' => '23',
                'grupo' => 'Serviços de programação e comunicação visual, desenho industrial e congêneres.',
                'servicos' => [
                    ['codigo' => '23.01', 'descricao' => 'Serviços de programação e comunicação visual, desenho industrial e congêneres.'],
                ]
            ],
            [
                'codigo' => '24',
                'grupo' => 'Serviços de chaveiros, confecção de carimbos, placas, sinalização visual, banners, adesivos e congêneres.',
                'servicos' => [
                    ['codigo' => '24.01', 'descricao' => 'Serviços de chaveiros, confecção de carimbos, placas, sinalização visual, banners, adesivos e congêneres.'],
                ]
            ],
            [
                'codigo' => '25',
                'grupo' => 'Serviços funerários.',
                'servicos' => [
                    ['codigo' => '25.01', 'descricao' => 'Funerais, inclusive fornecimento de caixão, urna ou esquifes; aluguel de capela; transporte do corpo cadavérico; fornecimento de flores, coroas e outros paramentos; desembaraço de certidão de óbito; fornecimento de véu, essa e outros adornos; embalsamento, embelezamento, conservação ou restauração de cadáveres.'],
                    ['codigo' => '25.02', 'descricao' => 'Cremação de corpos e partes de corpos cadavéricos.'],
                    ['codigo' => '25.03', 'descricao' => 'Planos ou convênio funerários.'],
                    ['codigo' => '25.04', 'descricao' => 'Manutenção e conservação de jazigos e cemitérios.'],
                ]
            ],
            [
                'codigo' => '26',
                'grupo' => 'Serviços de coleta, remessa ou entrega de correspondências, documentos, objetos, bens ou valores, inclusive pelos correios e suas agências franqueadas; courrier e congêneres.',
                'servicos' => [
                    ['codigo' => '26.01', 'descricao' => 'Serviços de coleta, remessa ou entrega de correspondências, documentos, objetos, bens ou valores, inclusive pelos correios e suas agências franqueadas; courrier e congêneres.'],
                ]
            ],
            [
                'codigo' => '27',
                'grupo' => 'Serviços de assistência social.',
                'servicos' => [
                    ['codigo' => '27.01', 'descricao' => 'Serviços de assistência social.'],
                ]
            ],
            [
                'codigo' => '28',
                'grupo' => 'Serviços de avaliação de bens e serviços de qualquer natureza.',
                'servicos' => [
                    ['codigo' => '28.01', 'descricao' => 'Serviços de avaliação de bens e serviços de qualquer natureza.'],
                ]
            ],
            [
                'codigo' => '29',
                'grupo' => 'Serviços de biblioteconomia.',
                'servicos' => [
                    ['codigo' => '29.01', 'descricao' => 'Serviços de biblioteconomia.'],
                ]    
            ],
            
            [
                'codigo' => '30',
                'grupo' => 'Serviços de biologia, biotecnologia e química.',
                'servicos' => [
                    ['codigo' => '30.01', 'descricao' => 'Serviços de biologia, biotecnologia e química.'],
                ]
            ],
            [
                'codigo' => '31',
                'grupo' => 'Serviços técnicos em edificações, eletrônica, eletrotécnica, mecânica, telecomunicações e congêneres.',
                'servicos' => [
                    ['codigo' => '31.01', 'descricao' => 'Serviços técnicos em edificações, eletrônica, eletrotécnica, mecânica, telecomunicações e congêneres.'],
                ]
            ],
            [
                'codigo' => '32',
                'grupo' => 'Serviços de desenhos técnicos.',
                'servicos' => [
                    ['codigo' => '32.01', 'descricao' => 'Serviços de desenhos técnicos.'],
                ]
            ],
            [
                'codigo' => '33',
                'grupo' => 'Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres.',
                'servicos' => [
                    ['codigo' => '33.01', 'descricao' => 'Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres.'],
                ]
            ],
            [
                'codigo' => '34',
                'grupo' => 'Serviços de investigações particulares, detetives e congêneres.',
                'servicos' => [
                    ['codigo' => '34.01', 'descricao' => 'Serviços de investigações particulares, detetives e congêneres.'],
                ]
            ],
            [
                'codigo' => '35',
                'grupo' => 'Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas.',
                'servicos' => [
                    ['codigo' => '35.01', 'descricao' => 'Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas.'],
                ]
            ],
            [
                'codigo' => '36',
                'grupo' => 'Serviços de meteorologia.',
                'servicos' => [
                    ['codigo' => '36.01', 'descricao' => 'Serviços de meteorologia.'],
                ]
            ],
            [
                'codigo' => '37',
                'grupo' => 'Serviços de artistas, atletas, modelos e manequins.',
                'servicos' => [
                    ['codigo' => '37.01', 'descricao' => 'Serviços de artistas, atletas, modelos e manequins.'],
                ]
            ],
            [  
                'codigo' => '38',
                'grupo' => 'Serviços de museologia.',
                'servicos' => [
                    ['codigo' => '38.01', 'descricao' => 'Serviços de museologia.'],
                ]
            ],            
            [
                'codigo' => '39',
                'grupo' => 'Serviços de ourivesaria e lapidação.',
                'servicos' => [
                    ['codigo' => '39.01', 'descricao' => 'Serviços de ourivesaria e lapidação (quando o material for fornecido pelo tomador do serviço).'],
                ]
             
            ],
            [
                'codigo' => '40',
                'grupo' => 'Serviços relativos a obras de arte sob encomenda.',
                'servicos' => [
                    ['codigo' => '40.01', 'descricao' => 'Obras de arte sob encomenda.'],
                ]
            ],        
            

        ];

        return $lista;
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
