<?php

namespace App\Http\Controllers\api\Finanzas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finanzas\VoucherResource;
use App\Models\Empresa\Voucher;
use Illuminate\Http\Request;

class ApiVoucherController extends Controller
{
    public function show(Voucher $voucher){
        $voucher->loadMissing('venta');
        return response( new VoucherResource($voucher), 201);
    }
}
