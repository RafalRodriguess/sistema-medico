<?php

namespace App\Http\Controllers\Comercial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PainelInicial extends Controller {

    public function index()
    {

       return view('comercial.home');
    }

}