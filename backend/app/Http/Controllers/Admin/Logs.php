<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Logs extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_logs');

        return view('admin.logs/lista');
    }

}
