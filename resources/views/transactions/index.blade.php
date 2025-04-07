@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>List Transaksi</h2>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">+ Tambah Transaksi</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="transactionsTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $trx)
                    <tr>
                        <td>{{ $trx->transaction_code }}</td>
                        <td>{{ $trx->customer_name }} <br> <small>{{ $trx->customer_number }}</small></td>
                        <td>{{ $trx->product->title ?? '-' }}</td>
                        <td>{{ $trx->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $trx->status_payment == 'paid' ? 'success' : ($trx->status_payment == 'cancel' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($trx->status_payment) }}
                            </span>
                        </td>
                        <td>Rp{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transactions.edit', $trx) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>
                            <form action="{{ route('transactions.destroy', $trx) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transactionsTable').DataTable();
    });
</script>
@endpush