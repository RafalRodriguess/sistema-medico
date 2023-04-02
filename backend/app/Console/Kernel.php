<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        /* WHATSAPP */

        //AUTOMAÇÃO AGENDA CONFIRMAÇÕES
        $schedule->command('automacao:whatsapp')
            ->everyTenMinutes()
            ->between('08:00', '22:00')
            ->withoutOverlapping();
            
        
        //AUTOMAÇÃO AGENDA DIÁRIA PRESTADOR
        $schedule->command('automacao-prestador:whatsapp-diario')
            ->hourly()
            ->between('15:00', '22:00')
            ->withoutOverlapping();

        //AUTOMAÇÃO ANIVERSARIANTES
        $schedule->command('automacao-pacientes:whatsapp-aniversario')
            ->dailyAt('08:00')
            ->withoutOverlapping();

        /* FIM WHATSAPP */

        


        /* SANCOOP */

        //CRIANDO LOTES LOCAL E API E ADICIONANDO GUIAS AO LOCAL
        /*
        $schedule->command('automacao:sancoop')
        ->hourly()
        ->between('20:00', '22:00')
        ->withoutOverlapping();
        */

        //TRANSMITINDO AS GUIAS PARA API
        /*
        $schedule->command('automacao:sancoop-transmissao')
        ->dailyAt('19:00')
        ->withoutOverlapping();
        */

        /* FIM SANCOOP */



        //AUTOMAÇÃO ASAPLAN
        // $schedule->command('automacao-asaplan')
        //     ->twiceDaily(10, 21)
        //     ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
