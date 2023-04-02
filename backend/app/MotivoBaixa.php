<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MotivoBaixa extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'motivo_baixa';

    protected $fillable = [
        'id',
        'descricao',
        'instituicao_id',
        'slug'
    ];

     protected $casts = [
        'id' => 'interger',
        'descricao'=>'string',
        'instituicao_id'=>'interger',
     ];


     public static function boot() {
        parent::boot();

        // Depois de inserir
        static::creating(function ($item) {
            $item->slug = Str::slug($item->descricao);
        });

        // Depois de alterar
        static::updating(function ($item) {
            $item->slug = Str::slug($item->descricao);
        });
     }
}
