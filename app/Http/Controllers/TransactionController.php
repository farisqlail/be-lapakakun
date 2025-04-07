<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['product', 'category'])->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('transactions.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_number' => 'required',
            'id_product' => 'required|exists:products,id',
            'id_category' => 'required|exists:categories,id',
            'status_payment' => 'required|in:pending,paid,cancel',
            'discount' => 'nullable|numeric',
            'total_price' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['uuid'] = Str::uuid();
        $data['transaction_code'] = strtoupper(Str::random(5));

        Transaction::create($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $products = Product::all();
        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'products', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_number' => 'required',
            'id_product' => 'required|exists:products,id',
            'id_category' => 'required|exists:categories,id',
            'status_payment' => 'required|in:pending,paid,cancel',
            'discount' => 'nullable|numeric',
            'total_price' => 'required|numeric',
        ]);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function history()
    {
        $transactions = Transaction::with(['product', 'category', 'product'])->where('status_payment', 'paid')->get();
        return view('transactions.history', compact('transactions'));
    }
}
