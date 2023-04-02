<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersaoTiss extends Model
{
    use SoftDeletes;
    protected $table = 'versoes_tiss';

    protected $fillable = [
        'versao',
        'diretorio',
    ];
}
