<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ContaReceber extends Model
{

    use SoftDeletes;
	use TraitLogInstituicao;

    protected $table = "contas_receber";

    protected $fillable = [
        'pessoa_id',
        'num_parcela',
        'data_vencimento',
        'valor_parcela',
        'tipo_parcelamento',
        'status',
        'data_pago',
        'valor_pago',
        'forma_pagamento',
        'processada',
        'data_compensacao',
        'obs',
        'agencia',
        'conta',
        'banco',
        'data_emissao',
        'compensado',
        'emitido_recebido',
        'bom_para',
        'titular',
        'numero_cheque',
        'anual',
        'descricao',
        'instituicao_id',
        'conta_id',
        'plano_conta_id',
        'num_documento',
        'data_competencia',
        'tipo',
        'cancelar_parcela',
        'agendamento_id',
        'convenio_id',
        'odontologico_id',
        'movimentacao_id',
        'saidas_estoque_id',
        'conta_pai',
        'num_parcelas',
        'valor_total',
        'nota_id',
        'cod_aut',
        'taxa_cartao',
        'maquina_id',
        'apibb_numero',
        'apibb_linha_digitavel',
        'apibb_codigo_barra_numerico',
        'apibb_qrcode_url',
        'apibb_qrcode_tx_id',
        'apibb_qrcode_emv',
        'usuario_baixou_id'
    ];

    protected $cast = [
        'data_vencimento' => 'date',
        'data_compensacao' => 'date',
        'data_pago' => 'date',
        'anual' => 'boolean',
    ];

    //tipos
    const paciente = 'paciente';
    const convenio = 'convenio';

    //forma_pagamento_texto
    const dinheiro = "dinheiro";
    const transferencia_bancaria = 'transferencia_bancaria';
    const boleto_cobranca = 'boleto_cobranca';
    const nota_promissoria = 'nota_promissoria';
    const duplicata_mercantil = 'duplicata_mercantil';
    const cheque = 'cheque';
    const cartao_credito = 'cartao_credito';
    const cartao_debito = 'cartao_debito';
    const pix = 'pix';
    const convenio_pagamento = 'convenio_pagamento';
    const uso_credito = 'uso_credito';

    public static function forma_pagamento_texto($formaPagamento = null)
    {
        $data = [
            self::dinheiro => 'Dinheiro',
            self::transferencia_bancaria => 'Transferência bancária',
            self::boleto_cobranca => 'Boleto de cobrança',
            self::nota_promissoria => 'Nota promissória',
            self::duplicata_mercantil => 'Duplicata mercantil',
            self::cheque => 'Cheque',
            self::cartao_debito => 'Cartão de débito',
            self::cartao_credito => 'Cartão de crédito',
            self::pix => 'Pix',
            self::convenio_pagamento => 'Convenio',
            self::uso_credito => 'Uso de Crédito',
        ];

        if($formaPagamento == null){
            return $data;
        }else{
            return $data[$formaPagamento];
        }
    }

    public static function formas_pagamento()
    {
        return [
            self::dinheiro,
            self::transferencia_bancaria,
            self::boleto_cobranca,
            self::nota_promissoria,
            self::duplicata_mercantil,
            self::cheque,
            self::cartao_debito,
            self::cartao_credito,
            self::pix,
            self::convenio_pagamento,
            self::uso_credito,
        ];
    }

    public static function tipos_texto_all($tipo = null)
    {
        $data = [
            self::paciente => 'Paciente',
            self::convenio => 'Convênio',
        ];

        if($tipo == null){
            return $data;
        }else{
            return $data[$tipo];
        }
    }

    public static function tipos()
    {
        return [
            self::paciente,
            self::convenio,
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
    
    public function pacienteTrashed()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->withTrashed();
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function contaCaixa()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    public function planoConta()
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id');
    }
    
    public function planoContaTrashed()
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id')->withTrashed();
    }

    public function agendamentos()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }

    public function odontologico()
    {
        return $this->belongsTo(OdontologicoPaciente::class, 'odontologico_id');
    }

    public function movimentacaoReceber()
    {
        return $this->hasMany(Movimentacao::class, 'conta_receber_id');
    }

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_id');
    }

    public function scopeSearch(Builder $query, string $formaPagamento = '', int $status = 3): Builder
    {
        if($status == 0 || $status == 1){
            $query->where('status', "{$status}");
        }

        if(!empty($formaPagamento))
        {
            $query->where('forma_pagamento', "{$formaPagamento}");
        }

        // if(preg_match('/^\d+$/', $formaPagamento))
        // {
        //     return $query->where('id','like', "{$formaPagamento}%");
        // }

        return $query->orderBy('data_vencimento', 'DESC');
    }

    public function scopeSearchFinanceiro(Builder $query, string $formaPagamento = '', int $status = 3, string $tipo = '', string $search = '', int $tipo_id = 0, string $data_inicio = '', string $data_fim = '', int $conta_id = 0, int $plano_conta_id = 0): Builder
    {
        if($status == 0 || $status == 1){
            $query->where('status', "{$status}");
        }

        if(!empty($formaPagamento))
        {
            $query->where('forma_pagamento', "{$formaPagamento}");
        }

        if($tipo == 'paciente'){

            if($tipo_id == 0){
                $query->where('tipo', $tipo);
            }else{
                $query->where('pessoa_id', $tipo_id);
            }

        }else if($tipo == 'convenio') {

            if($tipo_id == 0){
                $query->where('tipo', $tipo);
            }else{
                $query->where('convenio_id', $tipo_id);
            }

        }

        if(!empty($data_inicio)){
            // $query->where('data_vencimento', '>=', $this->inverteData($data_inicio));
            $query->where('data_vencimento', '>=', $data_inicio);
        }
        if(!empty($data_fim)){
            // $query->where('data_vencimento', '<=', $this->inverteData($data_fim));
            $query->where('data_vencimento', '<=', $data_fim);
        }

        if(preg_match('/^\d+$/', $search))
        {
            $query->where('id','like', "{$search}%");
        }else{
            if(!empty($search)){
                $query->where('descricao', 'like', "%{$search}%");
            }
        }

        if($conta_id != 0){
            $query->where('conta_id', $conta_id);
        }

        if($plano_conta_id != 0){
            $query->where('plano_conta_id', $plano_conta_id);
        }

        $query->with('paciente');
        $query->with('convenio');
        $query->with('agendamentos.conveniosProcedimentos.convenios');
        return $query->orderBy('id', 'DESC');
        // return $query->orderBy('data_vencimento', 'DESC');
    }

    function inverteData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    public function clienteReceber()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_id');
    }

    public function scopeSearchDemonstrativo(Builder $query, $dados): Builder
    {

        $query->select('created_at', 'data_vencimento', 'data_pago', 'valor_pago as valor', 'data_compensacao', 'num_parcela', 'forma_pagamento', 'status', 'valor_parcela', 'descricao', 'plano_conta_id', 'conta_id', DB::raw("'conta_receber' AS `natureza`"), 'id', DB::raw("'prestador_id'"), 'pessoa_id', 'obs');

        $query->where('cancelar_parcela', "0");

        $query->with(['planoConta', 'contaCaixa', 'paciente', 'prestador']);

        if(!empty($dados['data_inicio'])){
            if($dados['tipo_pesquisa'] != 'bancaria'){
                $query->where($dados['tipo_pesquisa'], '>=', $dados['data_inicio']." 00:00:00");
            }else{
                $query->where(DB::raw('IF(`data_compensacao` IS NOT NULL, `data_compensacao`, `data_pago`)'), '>=', $dados['data_inicio']);
            }
        }

        if(!empty($dados['data_fim'])){
            if($dados['tipo_pesquisa'] != 'bancaria'){
                $query->where($dados['tipo_pesquisa'], '<=', $dados['data_fim']." 23:59:59");
            }else{
                $query->where(DB::raw('IF(`data_compensacao` IS NOT NULL, `data_compensacao`, `data_pago`)'), '<=', $dados['data_fim']);
            }
        }

        if($dados['status_id'] == 0 || $dados['status_id'] == 1){
            $query->where('status', "{$dados['status_id']}");
        }

        if(!empty($dados['conta_id'])){
            $query->where('conta_id', $dados['conta_id']);
        }

        if(!empty($dados['plano_conta_id'])){
            $query->where('plano_conta_id', $dados['plano_conta_id']);
        }

        if(!empty($dados['formaPagamento']))
        {
            $query->where('forma_pagamento', "{$dados['formaPagamento']}");
        }

        if(!empty($dados['menor'])){
            $dados['menor'] = str_replace(',', '.', $dados['menor']);
            $dados['menor'] = floatval($dados['menor']);
            $query->where('valor_parcela', '<=', $dados['menor']);
        }

        if(!empty($dados['maior'])){
            $dados['maior'] = str_replace(',', '.', $dados['maior']);
            $dados['maior'] = floatval($dados['maior']);
            $query->where('valor_parcela', '>=', $dados['maior']);
        }

        if($dados['natureza'] == 'conta_pagar'){
            return $query->where('id', 0);
        }else{
            return $query;
        }
    }

    public function scopeSearchDemonstrativoSum(Builder $query, $dados): Builder
    {

        $query->select(DB::raw("SUM(IF(valor_pago = 'null', valor_parcela, valor_pago)) AS valor"));

        $query->where('cancelar_parcela', "0");

        if(!empty($dados['data_inicio'])){
            if($dados['tipo_pesquisa'] != 'bancaria'){
                $query->where($dados['tipo_pesquisa'], '>=', $dados['data_inicio']." 00:00:00");
            }else{
                $query->where(DB::raw('IF(`data_compensacao` IS NOT NULL, `data_compensacao`, `data_pago`)'), '>=', $dados['data_inicio']);
            }
        }

        if(!empty($dados['data_fim'])){
            if($dados['tipo_pesquisa'] != 'bancaria'){
                $query->where($dados['tipo_pesquisa'], '<=', $dados['data_fim']." 23:59:59");
            }else{
                $query->where(DB::raw('IF(`data_compensacao` IS NOT NULL, `data_compensacao`, `data_pago`)'), '<=', $dados['data_fim']);
            }
        }

        if($dados['status_id'] == 0 || $dados['status_id'] == 1){
            $query->where('status', "{$dados['status_id']}");
        }

        if(!empty($dados['conta_id'])){
            $query->where('conta_id', $dados['conta_id']);
        }

        if(!empty($dados['plano_conta_id'])){
            $query->where('plano_conta_id', $dados['plano_conta_id']);
        }

        if(!empty($dados['formaPagamento']))
        {
            $query->where('forma_pagamento', "{$dados['formaPagamento']}");
        }

        if(!empty($dados['menor'])){
            $dados['menor'] = str_replace(',', '.', $dados['menor']);
            $dados['menor'] = floatval($dados['menor']);
            $query->where('valor_parcela', '<=', $dados['menor']);
        }

        if(!empty($dados['maior'])){
            $dados['maior'] = str_replace(',', '.', $dados['maior']);
            $dados['maior'] = floatval($dados['maior']);
            $query->where('valor_parcela', '>=', $dados['maior']);
        }

        return $query;
    }

    public function scopeGetParcelasOdontologicas(Builder $query, $dados): Builder
    {
        $query->selectRaw('COUNT(forma_pagamento) as qtd_parcelas, SUM(IF(`valor_pago` IS NOT NULL, `valor_pago`, `valor_parcela`)) as valor_parcela, forma_pagamento');
        $query->whereIn('conta_id', $dados['contas']);
        $query->whereIn('forma_pagamento', $dados['formas_pagamento']);
        $query->groupBy('forma_pagamento');
        return $query;
    }

    
    public function scopeRelatorioContasReceber(Builder $query, $dados): Builder
    {
        $query->whereBetween($dados['tipo_pesquisa'], [$dados['data_inicio'].' 00:00:00', $dados['data_fim']." 23:59:59"]);

        if(!empty($dados['conta_id'])){
            $query->where('conta_id', $dados['conta_id']);
        }

        if(!empty($dados['formaPagamento'])){
            $query->where('forma_pagamento', $dados['formaPagamento']);
        }

        if(!empty($dados['plano_conta_caixa_id'])){
            $query->where('plano_conta_id', $dados['plano_conta_caixa_id']);
        }

        if(!empty($dados['status'])){
            if($dados['status'] == 'a_vencer'){
                $query->where('data_vencimento', '>=', date("Y-m-d"));
            }else if($dados['status'] == 'vencidas'){
                $query->where('data_vencimento', '<', date("Y-m-d"));
            }
        }

        return $query->whereNull('movimentacao_id')->orderBy('data_vencimento', 'ASC');
    }
}
