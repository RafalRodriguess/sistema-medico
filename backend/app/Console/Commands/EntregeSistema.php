<?php

namespace App\Console\Commands;

use App\Pedido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EntregeSistema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrege:sistema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Confirma pedido entregue pelo sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pedidos = Pedido::where('entrega_comercial', '!=', null)->where(function($query){
            $query->orWhere('status_pedido', 'enviado');
            $query->orWhere('status_pedido', 'disponivel');
        })->where('entrega_comercial','<=',date('Y-m-d H:i:s', strtotime('-1 day')))->get();

        DB::transaction(function() use($pedidos) {
            $dados = [
                'entrega_sistema' => date('Y-m-d H:i:s'),
                'status_pedido' => 'entregue',
            ];
            foreach ($pedidos as $key => $value) {
                $value->update($dados);
                event(new \App\Events\PedidoTimeline($value,"Pedido entregue",null,json_encode($value->getChanges()) ));
            }
        });
    }
}
