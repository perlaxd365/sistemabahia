<?php

namespace App\Livewire\Imagen;

use App\Models\ImagenInforme;
use App\Models\ImagenOrden;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Resultados extends Component
{

    use WithFileUploads;
    public $id_orden;
    public $orden;
    public $informes = [];
    public $archivos = [];
    public $archivos_existentes = [];
    public $paciente;
    public $atencion;
    public $historia;
    //imagenes
    public $archivo_url, $archivo_url_update;
    public function mount($id_orden)
    {
        $this->id_orden = $id_orden;
        $this->orden = ImagenOrden::with([
            'atencion.paciente',
            'detalles.estudio.area',
            'detalles.informe'
        ])->findOrFail($id_orden);

        //paciente
        $this->atencion = $this->orden->atencion;
        $this->paciente = $this->atencion->paciente;
        $this->historia = $this->atencion->historia;

        // Inicializar informes
        foreach ($this->orden->detalles as $detalle) {
            $this->informes[$detalle->id_detalle_imagen] =
                $detalle->informe
                ? $detalle->informe->informe
                : '';
            $this->archivos_existentes[$detalle->id_detalle_imagen] =
                $detalle->informe?->archivo;
        }
    }

    public function guardar()
    {
        DB::beginTransaction();

        try {

            foreach ($this->orden->detalles as $detalle) {

                $data = [
                    'informe' => $this->informes[$detalle->id_detalle_imagen] ?? null,
                    'fecha_informe' => now(),
                ];

                // 👇 SOLO si hay archivo nuevo
                if (isset($this->archivos[$detalle->id_detalle_imagen])) {

                    $archivo = $this->archivos[$detalle->id_detalle_imagen];

                    $extension = strtolower($archivo->getClientOriginalExtension());

                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {

                        // ☁️ Cloudinary (imágenes)
                        $path = $archivo->store('informes_imagen', 'cloudinary');
                        $url  = Storage::disk('cloudinary')->url($path);
                    } else {

                        // 📄 PDF → storage público
                        $path = $archivo->store('informes_pdf', 'public');
                        $url  = asset('storage/' . $path);
                    }

                    $data['archivo'] = $url;
                }

                ImagenInforme::updateOrCreate(
                    ['id_detalle_imagen' => $detalle->id_detalle_imagen],
                    $data
                );
            }

            DB::commit();

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Resultados actualizados correctamente',
                'message' => 'Resultados agregados'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Error al guardar resultados por ' . $e->__toString(),
                'message' => 'Comunicar al encargado'
            ]);
        }
    }
    private function cloudinaryPublicIdFromUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = ltrim($path, '/');
        $path = preg_replace('/^.*\/upload\/v\d+\//', '', $path);

        return pathinfo($path, PATHINFO_DIRNAME) . '/' .
            pathinfo($path, PATHINFO_FILENAME);
    }
    public function eliminarArchivo($idDetalle)
    {
        $informe = ImagenInforme::where('id_detalle_imagen', $idDetalle)->first();

        if (!$informe || !$informe->archivo) return;

        $publicId = str_starts_with($informe->archivo, 'http')
            ? $this->cloudinaryPublicIdFromUrl($informe->archivo)
            : $informe->archivo;

        Storage::disk('cloudinary')->delete($publicId);



        // 🔥 Inicializar Cloudinary SDK (OBLIGATORIO)
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);

        // 🗑️ Eliminar archivo REAL
        (new UploadApi())->destroy($publicId);


        // 🧹 limpiar BD
        $informe->update(['archivo' => null]);

        // 🧹 limpiar vista
        unset($this->archivos_existentes[$idDetalle]);

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Se eliminó correctamente',
            'message' => 'Archivo eliminado.'
        ]);
    }

    public function finalizar()
    {
        $this->guardar();
        $this->orden->update(['profesional' => auth()->user()->id]);
        $this->orden->update(['estado' => 'INFORMADO']);
        // ✅ Redireccionar al listado de órdenes
        return redirect()->route('imagen.ordenes');
    }
    public function render()
    {
        return view('livewire.imagen.resultados');
    }
}
