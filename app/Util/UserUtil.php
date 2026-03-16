<?php

use App\Models\Atencion;
use App\Models\Historia;
use App\Models\HistoriaCounter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserUtil
{

    public static function getUserByID($id)
    {
        $user = User::find($id);
        if ($user) {
            # code...
            return $user;
        }
    }

    public static function getUserMedicoByAtencionID($id_atencion)
    {
        $atencion = Atencion::find($id_atencion);
        if ($atencion) {
            # code...
            $user = User::find($atencion->id_medico);
            if ($user) {
                # code...
                return $user;
            }
        }
    }
}
