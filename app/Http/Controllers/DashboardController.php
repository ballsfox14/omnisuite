<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Entities\Tool;
use Modules\Inventory\Entities\Kit;
use Modules\Inventory\Entities\Loan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas
        $totalTools = Tool::count();
        $totalKits = Kit::count();
        $activeLoans = Loan::whereNull('returned_at')->count();
        $overdueLoans = Loan::whereNull('returned_at')
            ->where('loaned_at', '<', Carbon::now()->subDays(7)) // Ejemplo: préstamos con más de 7 días
            ->count();

        // Últimos 5 préstamos con relaciones
        $recentLoans = Loan::with(['user', 'items.loanable'])
            ->latest('loaned_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('totalTools', 'totalKits', 'activeLoans', 'overdueLoans', 'recentLoans'));
    }
}