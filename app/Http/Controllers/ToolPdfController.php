<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Inventory\Entities\Tool;
use Illuminate\Http\Request;

class ToolPdfController extends Controller
{
    public function exportCodes()
    {
        $tools = Tool::orderBy('code')->get();

        $data = [
            'tools' => $tools,
            'fecha' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('pdf.tools-codes', $data);

        // Descargar el PDF con nombre descriptivo
        return $pdf->download('codigos-herramientas-' . now()->format('Y-m-d') . '.pdf');
    }
}