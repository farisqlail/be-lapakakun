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

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
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
                            <form action="{{ route('transactions.provide.single', $trx) }}" method="POST" class="d-inline">
                                @csrf
                                @if ($trx->account)
                                <button type="button" class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#detailModal"
                                    data-email="{{ $trx->account->email }}"
                                    data-number="{{ $trx->account->number }}"
                                    data-product="{{ $trx->account->product->title ?? '-' }}">
                                    Lihat Akun
                                </button>
                                @else
                                <button type="button" class="btn btn-success btn-sm"
                                    data-toggle="modal"
                                    data-target="#provideModal"
                                    data-uuid="{{ $trx->uuid }}"
                                    data-kode="{{ $trx->transaction_code }}"
                                    data-customer="{{ $trx->customer_name }}">
                                    Provide Akun
                                </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Provide Akun -->
<div class="modal fade" id="provideModal" tabindex="-1" role="dialog" aria-labelledby="provideModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="provideForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="provideModalLabel">Konfirmasi Provide Akun</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan memberikan akun untuk transaksi:</p>
                    <ul class="mb-0">
                        <li><strong>Kode Transaksi:</strong> <span id="kodeTransaksi"></span></li>
                        <li><strong>Customer:</strong> <span id="customerTransaksi"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Berikan Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Akun -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Akun</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="mb-0">
                    <li><strong>Produk:</strong> <span id="detailProduct"></span></li>
                    <li><strong>Email:</strong> <span id="detailEmail"></span></li>
                    <li><strong>Nomor:</strong> <span id="detailNumber"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transactionsTable').DataTable();

        $('#provideModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const uuid = button.data('uuid');
            const kode = button.data('kode');
            const customer = button.data('customer');
            const action = "{{ url('transactions') }}/" + uuid + "/provide-account";

            $('#provideForm').attr('action', action);
            $('#kodeTransaksi').text(kode);
            $('#customerTransaksi').text(customer);
        });

        $('#detailModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            $('#detailEmail').text(button.data('email'));
            $('#detailNumber').text(button.data('number'));
            $('#detailProduct').text(button.data('product'));
        });
    });
</script>
@endpush