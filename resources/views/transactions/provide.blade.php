@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h3>Daftar Akun untuk Transaksi</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Customer</th>
                        <th>Email Akun</th>
                        <th>Nomor Akun</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($provided as $p)
                    <tr>
                        <td>{{ $p['transaction_code'] }}</td>
                        <td>{{ $p['customer_name'] }}</td>
                        <td>{{ $p['email'] ?? '-' }}</td>
                        <td>{{ $p['number'] ?? '-' }}</td>
                        <td>{{ $p['product'] ?? '-' }}</td>
                        <td>{{ $p['category'] ?? '-' }}</td>
                        <td>{{ $p['note'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection