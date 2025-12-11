<?php

use Carbon\Carbon;

class PacienteUtil
{
    public static function getFechaCompleta($fecha)
    {
        $fecha = new Carbon($fecha);
        $fecha->setLocale('es');
        return $fecha->isoFormat('dddd D \d\e MMMM \d\e\l Y H:mm A');
    }

}
 