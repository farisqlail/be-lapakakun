<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account' => 'required|string|max:50',
            'name' => 'required|string|max:255',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account' => 'required|string|max:50',
            'name' => 'required|string|max:255',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
