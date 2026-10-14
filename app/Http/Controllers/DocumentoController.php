<?php
namespace App\Http\Controllers;

use App\Models\DocumentoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function store(Request $request, $id_vehiculo)
    {
        $request->validate([
            'tipo_documento' => 'required|in:soat,tecnomecanica,tarjeta_propiedad',
            'archivo' => 'required|file|mimes:pdf,jpg,png|max:2048' // Máximo 2MB
        ]);

        $vehiculo = Vehiculo::findOrFail($id_vehiculo);

        // Guardar archivo físicamente en la carpeta storage/app/public/documentos
        $rutaArchivo = $request->file('archivo')->store('documentos', 'public');

        DocumentoVehiculo::create([
            'id_vehiculo' => $vehiculo->id_vehiculo,
            'tipo_documento' => $request->tipo_documento,
            'archivo_url' => $rutaArchivo,
            'estado_validacion' => 'pendiente',
            // En un sistema real, el usuario digita las fechas o una IA las extrae del PDF
            'fecha_expedicion' => date('Y-m-d'),
            'fecha_vencimiento' => date('Y-m-d', strtotime('+1 year'))
        ]);

        return back()->with('success', 'Documento subido correctamente. En revisión.');
    }
}