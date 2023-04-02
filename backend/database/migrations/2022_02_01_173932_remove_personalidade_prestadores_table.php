<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Prestador;

class RemovePersonalidadePrestadoresTable extends Migration
{
    protected $migration_table = 'prestadores';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Convertendo personalidade em vinculo
        $prestadores = Prestador::all();
        foreach($prestadores as $prestador) {
            // Caso ele não tenha um vinculo de personalidade
            if($prestador->prestadorVinculos()->whereIn('vinculo_id', ['5', '6'])->count() == 0) {
                if($prestador->personalidade == 1) {
                    $prestador->prestadorVinculos()->create(['vinculo_id' => 5]);
                } else {
                    $prestador->prestadorVinculos()->create(['vinculo_id' => 6]);
                }
            }
        }

        Schema::table($this->migration_table, function (Blueprint $table) {
            $table->dropColumn('personalidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->migration_table, function (Blueprint $table) {
            $table->int('personalidade');
        });

        // Convertendo Vinculo em personalidade
        $prestadores = Prestador::all();
        foreach($prestadores as $prestador) {
            // Caso ele tenha um vinculo de pessoa física
            if($prestador->prestadorVinculos()->where('vinculo_id', '=', '5')->count() != 0) {
                $prestador->update(['personalidade' => 1]);
            } else {
                $prestador->update(['personalidade' => 2]);
            }
        }
    }
}
