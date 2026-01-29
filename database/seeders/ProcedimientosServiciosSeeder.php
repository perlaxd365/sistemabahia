<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcedimientosServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================
        // TIPO
        // ============================

        $tipo = TipoServicio::firstOrCreate(
            ['nombre_tipo_servicio' => 'PROCEDIMIENTOS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPOS
        // ============================

        $subProced = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'PROCEDIMIENTOS CLINICOS', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subDocs = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'DOCUMENTOS ADMINISTRATIVOS', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subLev = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'LEVANTAMIENTO DE OBSERVACION', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subDescanso = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'DESCANSO MEDICO', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // PROCEDIMIENTOS CLINICOS
        // ============================

        $procedimientos = [

            ['ALQUILER DE CONSULTORIO', 20],
            ['ALQUILER DE CONSULTORIO (FERIADOS Y DOMINGO)', 30],
            ['ALQUILER DE CONSULTORIO POR PROCEDIMIENTOS MENORES', 30],
            ['ALQUILER DE CONSULTORIO POR PROCEDIMIENTOS MENORES (FERIADOS Y DOMINGO)', 50],
            ['ALQUILER DE EMERGENCIA POR RETIRO CLAVOS DR MEDINA', 80],
            ['CAUTERIZACION DE LUNARES', 120],
            ['CAUTERIZACION DE VERRUGAS HASTA 2', 70],
            ['CERTIFICADO DE BUENA SALUD', 125],
            ['CERTIFICADO DE MATRIMONIO', 150],
            ['CIRUGIA MENOR EXTRACCION DE LIPOMA PEQUEÑO', 120],
            ['COLOCACION DE LANTUS HASTA 20 UI', 20],
            ['COLOCACION DE ENEMA CON MATERIAL', 80],
            ['COLOCACION DE ENEMA SIN MATERIAL', 50],
            ['COLOCACION DE HIERRO', 120],
            ['COLOCACION DE VITAMINA C', 180],
            ['COLOCACION DE SONDA FOLEY', 100],
            ['COLOCACION DE FERULA MEDICINA GENERAL', 200],
            ['COLOCACION DE YESO MIEMBRO INFERIOR CORTO ADULTO', 350],
            ['COLOCACION DE YESO MIEMBRO INFERIOR LARGO ADULTO', 450],
            ['SUTURA POR PUNTO', 45],
            ['COLOCACION DE YESO MIEMBRO SUPERIOR LARGO ADULTO', 500],

            ['CURACION GRANDE', 50],
            ['CURACION PEQUEÑA', 30],
            ['CURACION POR PICADURA DE RAYA', 150],

            ['DEBRIDACION DE ABSCESO DR PUENTE', 900],
            ['DESIMPACTACION FECAL', 100],
            ['DRENAJE DE ABCESO MAMARIO PEQUEÑO', 120],
            ['DRENAJE DE RODILLA', 150],
            ['ELECTROCARDIOGRAMA', 100],

            ['HOSPITALIZACION POR DIA BASICO', 30],
            ['HOSPITALIZACION POR DIA COMPLETO', 150],

            ['EXTIRPACION DE QUISTE DE BARTOLINO', 1200],
            ['EXTRACCION DE SANGRE 1 UNIDAD', 100],
            ['INFILTRACION CON MATERIAL', 200],
            ['INTRAMUSCULAR', 10],
            ['LAPAROCENTESIS', 400],
            ['LARINGOSCOPIA DIRECTA', 300],

            ['LAVADO DE OIDO UNO', 50],
            ['LAVADO DE OIDO AMBOS', 80],
            ['LAVADO DE OIDO PEDIATRICO', 120],
            ['LAVADO DE OJOS', 120],
            ['LAVADO GASTRICO', 170],
            ['LAVADO NASAL BEBE', 50],

            ['LUXACION DE HOMBRO SEDACION LOCAL', 800],
            ['LUXACION DE HOMBRO SEDACION TOTAL', 1000],

            ['MANIOBRA HEIMLICH', 100],
            ['MAPA HOLTER', 200],
            ['NEBULIZACION 1 SESION', 45],
            ['OBSERVACION POR HORA', 15],
            ['OXIGENOTERAPIA', 80],
            ['PAPANICOLAOU', 100],

            ['PAQUETE LAB + RQ + EKG', 350],
            ['PAQUETE LAB + EKG + RQ', 350],
            ['PAQUETE LAB + EKG', 180],

            ['PESO Y TALLA', 2],
            ['PRUEBA SENSIBILIDAD PENICILINA', 50],

            ['RETIRO CUERPO EXTRAÑO ADULTO', 300],
            ['RETIRO CUERPO EXTRAÑO NIÑO', 350],
            ['RETIRO IMPLANTE SUBDERMICO', 100],
            ['RETIRO PUNTOS HASTA 10', 50],
            ['RETIRO PUNTOS HASTA 5', 30],
            ['RETIRO PUNTOS MAS DE 10', 70],
            ['RETIRO UÑA O UÑERO', 80],

            ['RETIRO YESO MIEMBRO INFERIOR MG', 150],
            ['RETIRO YESO MIEMBRO INFERIOR TRAUMA', 200],
            ['RETIRO YESO MIEMBRO SUPERIOR MG', 100],
            ['RETIRO YESO MIEMBRO SUPERIOR TRAUMA', 150],

            ['RIESGO QUIRURGICO CARDIO + EKG', 240],
            ['RIESGO QUIRURGICO INTERNISTA + EKG', 230],
            ['PAQUETE RIESGO QUIRURGICO COMPLETO', 280],

        ];

        foreach ($procedimientos as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subProced->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'codigo_sunat' => null,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // DOCUMENTOS ADMINISTRATIVOS
        // ============================

        $docs = [
            ['COPIA DE HISTORIA CLINICA', 30],
            ['INFORME MEDICO', 50],
        ];

        foreach ($docs as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subDocs->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // LEVANTAMIENTO OBSERVACION
        // ============================

        $lev = [
            ['LEVANTAMIENTO OBSERVACION MEDICINA INTERNA', 90],
            ['LEVANTAMIENTO OBSERVACION NEUMOLOGIA', 100],
            ['LEVANTAMIENTO OBSERVACION ENDOCRINO', 80],
            ['LEVANTAMIENTO OBSERVACION CARDIOLOGIA', 100],
        ];

        foreach ($lev as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subLev->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                ]
            );
        }

        // ============================
        // DESCANSO MEDICO
        // ============================

        Servicio::firstOrCreate(
            [
                'nombre_servicio' => 'DESCANSO MEDICO NEUROLOGIA',
                'id_subtipo_servicio' => $subDescanso->id_subtipo_servicio
            ],
            [
                'precio_servicio' => 350,
                'estado_servicio' => true,
            ]
        );
    }
}
