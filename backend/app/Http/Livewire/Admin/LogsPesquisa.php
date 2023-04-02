<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\ErrorsLog;

class LogsPesquisa extends Component
{

    use AuthorizesRequests;


    private  $logs;


    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_logs');
        $this->performQuery();
        return view('livewire.admin.logs-pesquisa', ['logs' => $this->logs]);
    }

    private function performQuery(): void
    {

        $query = ErrorsLog::query()->orderBy('record_datetime','desc');
        // $query->first();
        // dd((($query)));

        $this->logs = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
