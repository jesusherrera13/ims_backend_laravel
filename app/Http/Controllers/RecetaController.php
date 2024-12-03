<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RecetaController extends Controller
{
    public function index()
{
    $recetas = Receta::with(['paciente', 'especialidad'])
        ->select('id_receta', 'paciente_id', 'especialidad_id', 'nombre','descrip', 'fecha', 'sta')
        ->get()
        ->map(function($receta) {
            return [
                'id_receta' => $receta->id_receta,
                'paciente_id' => $receta->paciente_id,
                'nombre_paciente' => $receta->paciente->nombre ?? null,  // Nombre del paciente
                'especialidad_id' => $receta->especialidad_id,
                'nombre_especialidad' => $receta->especialidad->nombre ?? null,  // Nombre de la especialidad
                'nombre' => $receta->nombre,
                'descrip' => $receta->descrip,
                'fecha' => $receta->fecha,
                'sta' => $receta->sta,
            ];
        });

    return response()->json($recetas, 200);
}


public function create(Request $request)
{
    $fields = $request->validate([
        //'paciente_id' => 'required|integer|exists:pacientes,id',
        'especialidad_id' => 'required|integer|exists:system_especialidades_medicas,id',
        'nombre' => 'required|string',
        'descrip' => 'required|string',
        'fecha' => 'required|date',
        'sta' => 'required|string|size:1',
    ]);

    $receta = Receta::create($fields);

    return response()->json($receta, 201);
}

// Obtener una receta específica (Para el modal de previsualización)
public function show($id)
{
    $receta = Receta::with(['paciente', 'especialidad'])
        ->select('id_receta', 'paciente_id', 'especialidad_id', 'nombre', 'descrip', 'fecha', 'sta')
        ->where('id_receta', $id)
        ->firstOrFail();

    return response()->json($receta, 200);
}

public function update(Request $request, Receta $receta)
{
    $fields = $request->validate([
        'paciente_id' => 'required|integer|exists:pacientes,id',
        'especialidad_id' => 'required|integer|exists:especialidades,id',
        'nombre' => 'required|string',
        'descrip' => 'required|string',
        'fecha' => 'required|date',
        'sta' => 'required|char:1',
    ]);

    $receta->update($fields);

    return response()->json($receta, 200);
}

public function destroy(Receta $receta)
{
    $receta->delete();
    return response()->json(['message' => 'Receta eliminada'], 200);
}

public function generatePDF($id)
{
    //dd("hola");
    $receta = Receta::with('especialidad')->findOrFail($id);

    // Verificar si el PDF ya existe en el almacenamiento
    $fileName = 'receta_' . $id . '.pdf';
    if (Storage::exists($fileName)) {
        $pdfContent = Storage::get($fileName);
    } else {
        $pdf = \PDF::loadView('recetas.pdf', compact('receta'));
        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isRemoteEnabled', true);

        $pdfContent = $pdf->output();
        Storage::put($fileName, $pdfContent); // Guardar en cache
    }

    return response($pdfContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Cache-Control', 'max-age=3600, public');
}

public function previewPDF(Request $request)
{

    // Datos temporales desde el request
    $data = [
        'nombre' => $request->input('nombre'),
        'especialidad_id' => $request->input('especialidad_id'),
        'descrip' => $request->input('descrip'),
        'fecha' => $request->input('fecha'),
        'sta' => $request->input('sta')
    ];

    // Genera el PDF usando una vista similar a 'recetas.pdf'
    $pdf = \PDF::loadView('recetas.preview', $data);
    $dompdf = $pdf->getDomPDF();
    $dompdf->set_option('isRemoteEnabled', true);

    // Retorna el PDF como respuesta en formato blob
    return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Cache-Control', 'no-store, no-cache');
}





}