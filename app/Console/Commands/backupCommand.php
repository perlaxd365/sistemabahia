<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupCommand extends Command
{
    protected $signature = 'app:backup-command';
    protected $description = 'Genera backup de la base de datos y lo sube a Wasabi';

    public function handle()
    {
        $ruta = storage_path('app/backups');

        if (!File::exists($ruta)) {
            File::makeDirectory($ruta, 0755, true);
        }

        $fecha = now()->format('Y-m-d_H-i-s');
        $nombreArchivo = "backup_{$fecha}.sql";
        $archivo = $ruta . DIRECTORY_SEPARATOR . $nombreArchivo;

        // AJUSTA ESTA RUTA SEGÚN TU LARAGON
        $mysqldump = 'C:\laragon\bin\mysql\mysql-8.0.40-winx64\bin\mysqldump.exe';

        $db   = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $comando = "\"{$mysqldump}\" -h{$host} -u{$user} --password=\"{$pass}\" {$db} > \"{$archivo}\"";

        exec($comando, $output, $status);

        if ($status !== 0 || !File::exists($archivo)) {
            $this->error('❌ Error al generar el backup');
            return;
        }

        $this->info('✅ Backup generado correctamente');
        $this->info($archivo);

        // Subir a Wasabi (S3)
        Storage::disk('s3')->put(
            'backups/' . $nombreArchivo,
            fopen($archivo, 'r')
        );

        $this->info('☁️ Backup subido a Wasabi correctamente');
    }
}
