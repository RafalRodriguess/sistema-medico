<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RelatoriosFinanceirosExport implements FromView, WithEvents
{
    protected $dados;
    protected $view;

    
    public function __construct($dados, $view)
    {
        $this->dados = $dados;
        $this->view = $view;
    }

    public function view(): View
    {
        return view($this->view, ['dados' => $this->dados]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $event->getWriter()
                    ->getDelegate()
                    ->getActiveSheet() 
                    ->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);
            }
        ];
    }

    // public static function beforeWriting(BeforeWriting $event)
    // {
    //     $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
    //     $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    // }
}
