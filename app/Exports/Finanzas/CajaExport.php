<?php

namespace App\Exports\Finanzas;

use App\Models\Finanzas\Venta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class CajaExport implements FromView
{

   public function view(): View
   {
        return view('exports.finanzas.caja', [
            'ventas' => Venta::whereDate('created_at', Carbon::today())->get()
        ]);
   }
}
