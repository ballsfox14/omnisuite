<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ToolsExport;
use App\Exports\KitsExport;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Inventory\Entities\Tool;
use Modules\Inventory\Entities\Kit;
use Modules\Inventory\Entities\Loan;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // Exportaciones Excel
    public function exportToolsExcel()
    {
        return Excel::download(new ToolsExport, 'herramientas.xlsx');
    }

    public function exportKitsExcel()
    {
        return Excel::download(new KitsExport, 'kits.xlsx');
    }

    public function exportLoansExcel()
    {
        return Excel::download(new LoansExport, 'prestamos.xlsx');
    }

    // Exportaciones PDF (simples, listados)
    public function exportToolsPdf()
    {
        $tools = Tool::orderBy('code')->get();
        $pdf = Pdf::loadView('reports.pdf.tools', compact('tools'));
        return $pdf->download('herramientas.pdf');
    }

    public function exportKitsPdf()
    {
        $kits = Kit::with('tools')->orderBy('code')->get();
        $pdf = Pdf::loadView('reports.pdf.kits', compact('kits'));
        return $pdf->download('kits.pdf');
    }

    public function exportLoansPdf()
    {
        $loans = Loan::with(['user', 'items.loanable'])->orderBy('loaned_at', 'desc')->get();
        $pdf = Pdf::loadView('reports.pdf.loans', compact('loans'));
        return $pdf->download('prestamos.pdf');
    }
}