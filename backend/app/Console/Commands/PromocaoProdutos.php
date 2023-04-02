<?php

namespace App\Console\Commands;

use App\Produto;
use Illuminate\Console\Command;

class PromocaoProdutos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produto:promocao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica produtos em promoção';

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
        Produto::where('promocao', 1)->whereDate('promocao_final', '<', date('Y-m-d'))->update(['promocao' => 0]);        
    }
}
