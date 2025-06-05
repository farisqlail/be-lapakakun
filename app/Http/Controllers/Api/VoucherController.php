<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return response()->json([
            'response' => 'success',
            'data' => $vouchers
        ]);
    }

    public function show($id) 
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'response' => 'error',
                'message' => 'Voucher not found'
            ], 404);
        }

        return response()->json([
            'response' => 'success',
            'data' => $voucher
        ]);
    }
}
