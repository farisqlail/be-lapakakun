<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCreatedMail;

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

        $transaction = Transaction::create($data);

        try {
            Mail::to('faris.riskilail@gmail.com')->send(new TransactionCreatedMail($transaction));
        } catch (\Exception $e) {
            \Log::error("Gagal kirim email transaksi: " . $e->getMessage());
        }

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

    public function provideAccount()
    {
        $transactions = Transaction::with(['product', 'category'])
            ->where('status_payment', 'paid') // misal hanya untuk yang sudah dibayar
            ->get();

        $provided = [];

        foreach ($transactions as $trx) {
            $accounts = Account::where('id_product', $trx->id_product)
                ->whereHas('product.categories', function ($q) use ($trx) {
                    $q->where('categories.id', $trx->id_category);
                })
                ->where('stock', '>', 0)
                ->get();

            if ($accounts->count() == 0) {
                $provided[] = [
                    'transaction_code' => $trx->transaction_code,
                    'customer_name' => $trx->customer_name,
                    'note' => 'Tidak ada akun tersedia'
                ];
                continue;
            }

            $selectedAccount = $accounts->first(); // Ambil yang pertama dengan stok
            $selectedAccount->decrement('stock'); // Kurangi stok

            $provided[] = [
                'transaction_code' => $trx->transaction_code,
                'customer_name'    => $trx->customer_name,
                'email'            => $selectedAccount->email,
                'number'           => $selectedAccount->number,
                'product'          => $selectedAccount->product->title,
                'category'         => $trx->category->name,
            ];
        }

        return view('transactions.provided', compact('provided'));
    }

    public function provideSingleAccount(Transaction $transaction)
    {
        if ($transaction->id_account) {
            return redirect()->back()->with('error', 'Transaksi ini sudah memiliki akun.');
        }

        $accounts = Account::where('id_product', $transaction->id_product)
            ->whereHas('product.categories', function ($q) use ($transaction) {
                $q->where('categories.id', $transaction->id_category);
            })
            ->where('stock', '>', 0)
            ->get();

        if ($accounts->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada akun tersedia untuk transaksi ini.');
        }

        $selected = $accounts->first();
        $selected->decrement('stock');

        $transaction->update(['id_account' => $selected->id]);

        return redirect()->back()->with('success', 'Akun berhasil diberikan: Email ' .
            $selected->email . ', Nomor: ' . $selected->number);
    }
}
