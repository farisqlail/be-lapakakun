@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Transactions History</h2>

            <table class="table table-bordered" id="historyTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $trx)
                    <tr>
                        <td>{{ $trx->transaction_code }}</td>
                        <td>
                            <strong>{{ $trx->customer_name }}</strong><br>
                            <small>{{ $trx->customer_number }}</small>
                        </td>
                        <td>{{ $trx->product->title ?? '-' }}</td>
                        <td>{{ $trx->category->name ?? '-' }}</td>
                        <td>Rp{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-success">Paid</span>
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
        $('#historyTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
</script>
@endpush