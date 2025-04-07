<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('product')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $products = Product::all();
        return view('accounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|exists:products,id',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
            'due_date' => 'required|date',
            'number' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        // Optional: simpan hash jika perlu
        // $data['password'] = bcrypt($request->password);

        Account::create($data);
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(Account $account)
    {
        $products = Product::all();
        return view('accounts.edit', compact('account', 'products'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'id_product' => 'required|exists:products,id',
            'email' => 'required|email|unique:accounts,email,' . $account->id,
            'password' => 'nullable|min:6',
            'due_date' => 'required|date',
            'number' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
            // Optional: bcrypt
            // $data['password'] = bcrypt($request->password);
        }

        $account->update($data);
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
