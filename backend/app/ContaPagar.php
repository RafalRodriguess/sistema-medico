<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ContaPagar extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "contas_pagar";

    protected $fillable = [
        'pessoa_id',
        'num_parcela',
        'data_vencimento',
        'valor_parcela',
        'status',
        'data_pago',
        'valor_pago',
        'forma_pagamento',
        'data_compensacao',
        'obs',
        'descricao',
        'conta_id',
        'plano_conta_id',
        'prestador_id',
        'numero_doc',
        'data_competencia',
        'tipo',
        'instituicao_id',
        'titular',
        'banco',
        'numero_cheque',
        'conta_pai',
        'total',
        'agencia',
        'conta',
        'data_emissao_nf',
        'nf_imposto',
        'desc_juros_multa',
        'chave_pix',
        'cartao_credito_id',
        'data_compra_credito',
        'movimentacao_id',
        'usuario_baixou_id'
    ];

    protected $cast = [
        'data_vencimento' => 'date',
        'data_compensacao' => 'date',
        'data_emissao_nf' => 'date',
        'nf_imposto' => 'boolean',
        'data_pago' => 'date',
    ];

    //tipos
    const paciente = 'paciente';
    const fornecedor = 'fornecedor';
    const prestador = 'prestador';
    const movimentacao = 'movimentacao';

    //formas pagamento
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
    
    public static function tipos_texto_all($tipo = null)
    {
        $data = [
            self::paciente => 'Pacientes',
            self::fornecedor => 'Fornecedor',
            self::prestador => 'Prestador',
            self::movimentacao => 'Movimentação',
        ];

        if($tipo == null){
            return $data;
        }else{
            return $data[$tipo];
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
    
    
    public static function tipos()
    {
        return [
            self::paciente,
            self::fornecedor,
            self::prestador,
            self::movimentacao,
        ];
    }
    
    public static function tipos_all()
    {
        return [
            self::paciente,
            self::fornecedor,
            self::prestador,
            self::movimentacao,
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->where('tipo', '1');
    }

    public function contaCaixa()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
        // return $this->hasMany(Conta::class, 'id', 'conta_id');
    }

    public function planoConta()
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id');
    }
    
    public function planoContaTrashed()
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id')->withTrashed();
    }
    
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->where('tipo', 3);
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function centroCusto()
    {
        return $this->belongsToMany(CentroCusto::class, 'contas_pagar_centros_custos', 'conta_pagar_id', 'centro_custo_id')->withPivot('valor');
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

        $query->with(['planoConta', 'contaCaixa', 'prestador', 'paciente']);

        return $query->orderBy('data_vencimento', 'DESC');
    }
    
    public function scopeSearchFinanceiro(Builder $query, string $formaPagamento = '', int $status = 3, string $tipo = '', string $search = '', int $tipo_id = 0, string $data_inicio = '', string $data_fim = '', int $conta_id = 0, int $plano_conta_id = 0, string $menor = '', string $maior = '', string $valor_total_nf = '', string $nota_fiscal = '', $contas): Builder
    {
        if($status == 0 || $status == 1){
            $query->where('status', "{$status}");
        }

        if(!empty($nota_fiscal)){
            $query->where('numero_doc', $nota_fiscal);
        }

        if(!empty($valor_total_nf)){
            $query->where('total', $valor_total_nf);
        }
        
        if(!empty($menor)){
            $menor = str_replace('.', '', $menor);
            $menor = str_replace(',', '.', $menor);
            $menor = floatval($menor);
            $query->where('valor_parcela', '>=', $menor);
        }
        
        if(!empty($maior)){
            $maior = str_replace('.', '', $maior);
            $maior = str_replace(',', '.', $maior);
            $maior = floatval($maior);
            $query->where('valor_parcela', '<=', $maior);
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

        }else if($tipo == 'prestador') {

            if($tipo_id == 0){
                $query->where('tipo', $tipo);
            }else{
                $query->where('prestador_id', $tipo_id);
            }

        }else if($tipo == 'fornecedor') {
            
            if($tipo_id == 0){
                $query->where('tipo', $tipo);
            }else{
                $query->where('pessoa_id', $tipo_id);
            }

        }else if($tipo == 'movimentacao') {
                
            $query->where('tipo', $tipo);

        }
        
        if(!empty($data_inicio)){
            $query->where('data_vencimento', '>=', $this->inverteData($data_inicio));
        }
        if(!empty($data_fim)){
            $query->where('data_vencimento', '<=', $this->inverteData($data_fim));
        }

        if(preg_match('/^\d+$/', $search))
        {
            $query->where(function($query) use($search){
                $query->where('id','like', "{$search}%");
                $query->orwhere('descricao', 'like', "%{$search}%");
            });
        }else{
            if(!empty($search)){
                $query->where('descricao', 'like', "%{$search}%");
            }
        }

        if($conta_id != 0){
            $query->where('conta_id', $conta_id);
        }else{
            $query->whereIn('conta_id', $contas);
        }
        
        if($plano_conta_id != 0){
            $query->where('plano_conta_id', $plano_conta_id);
        }

        $query->with('prestador');


        // return $query->orderBy('id', 'DESC');
        return $query->orderBy('data_vencimento', 'DESC');
    }

    function inverteData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    public function scopeGetAllParcelas(Builder $query, int $conta_pai):Builder
    {
        $query->where('conta_pai', $conta_pai);
        // $query->where('id', '!=', $id);

        return $query;
    }

    public function scopeSearchDemonstrativo(Builder $query, $dados): Builder
    {

        // $query->select('created_at', 'data_vencimento', 'data_pago', 'valor_pago as valor', 'data_compensacao', 'num_parcela', 'forma_pagamento', 'status', 'valor_parcela', 'descricao', 'plano_conta_id', 'conta_id', DB::raw("'conta_pagar' AS `natureza`"), DB::raw("'forma_recebimento' AS `forma_recebimento_id`"), 'id', 'prestador_id', DB::raw("'pessoa_id'"), 'obs');

        $query->select('created_at', 'data_vencimento', 'data_pago', 'valor_pago as valor', 'data_compensacao', 'num_parcela', 'forma_pagamento', 'status', 'valor_parcela', 'descricao', 'plano_conta_id', 'conta_id', DB::raw("'conta_pagar' AS `natureza`"), 'id', 'prestador_id', 'pessoa_id', 'obs');

        $query->with(['planoConta', 'contaCaixa', 'prestador', 'paciente', 'fornecedor']);

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
        
        if($dados['natureza'] == 'conta_receber'){
            return $query->where('id', 0);
        }else{
            return $query;
        }
    }

    public function scopeSearchDemonstrativoSum(Builder $query, $dados): Builder
    {

        $query->select(DB::raw("SUM(IF(valor_pago = 'null', valor_parcela, valor_pago)) AS valor"));

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

    public function scopeRelatorioContasPagar(Builder $query, $dados): Builder
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
    
    public function scopeGetContasPagar(Builder $query, $dados): Builder
    {
        $query->whereBetween('data_pago', [$dados['data_inicio'].' 00:00:00', $dados['data_fim']." 23:59:59"]);



        // if(!empty($dados['conta_id'])){
            $query->whereIn('conta_id', $dados['contas']);
        // }

        // if(!empty($dados['formaPagamento'])){
            $query->whereIn('forma_pagamento', $dados['formas_pagamento']);
        // }

        $query->where('instituicao_id', request()->session()->get('instituicao'));

        return $query->whereNull('movimentacao_id')
            ->where('status', 1)
            ->orderBy('data_pago', 'ASC');
    }
}
