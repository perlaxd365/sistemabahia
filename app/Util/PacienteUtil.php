<?php

use App\Models\Atencion;
use App\Models\Historia;
use App\Models\HistoriaCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PacienteUtil
{
    public static function addHistoria($id_paciente)
    {
        $historia = Historia::where("id_paciente", $id_paciente)->where("estado_historia", true)->first();

        //nro historia

        $prefijo = "CB"; // Clínica Bahía
        $fecha = now()->format("Ymd"); // AAAAMMDD

        $nro_historia = $prefijo . '-' . $fecha . '-' . str_pad($id_paciente, 4, '0', STR_PAD_LEFT);

        if (!$historia) {
            # code...
            $historia = Historia::create([
                'id_paciente' => $id_paciente,
                'nro_historia' => $nro_historia,
                'fecha_historia' => now(),
                'estado_historia' => true,
            ]);
        }

        return $historia->id_historia;
    }

    public static function getHistoria($id_paciente)
    {
        $historia = Historia::where("id_paciente", $id_paciente)->where("estado_historia", true)->first();
        if ($historia) {
            # code...
            return $historia->id_historia;
        }
    }
    
    public static function getNombreAtencion($id_atencion)
    {
        $atencion = Atencion::where("id_atencion", $id_atencion)->first();
        return $paciente = User::find($atencion->id_paciente)->name;
        
    }
}
