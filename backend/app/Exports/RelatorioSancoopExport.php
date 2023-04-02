<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RelatorioSancoopExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $dados;
    protected $view;

    
    public function __construct($dados, $view)
    {
        $this->dados = $dados;
        $this->view = $view;
    }

    public function view(): View 
    {
        return view($this->view, ['guias' => $this->dados]);
    }
}
