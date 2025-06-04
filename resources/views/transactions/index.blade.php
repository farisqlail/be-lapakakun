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

            <div class="table-responsive">
                <table class="table table-bordered" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $trx)
                        <tr>
                            <td>{{ $trx->transaction_code }}</td>
                            <td>{{ $trx->customer_name }}<br><small>{{ $trx->customer_number }}</small></td>
                            <td>{{ $trx->product->title ?? '-' }}</td>
                            <td>{{ $trx->category->name ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $trx->status_payment == 'paid' ? 'success' : ($trx->status_payment == 'cancel' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($trx->status_payment) }}
                                </span>
                            </td>
                            <td>{{ $trx->due_date }}</td>
                            <td>Rp{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('transactions.edit', $trx) }}" class="btn btn-warning btn-sm mb-1">
                                    <i class="fas fa-pen text-white"></i>
                                </a>
                                <form action="{{ route('transactions.provide.single', $trx) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if ($trx->account)
                                    <button type="button" class="btn btn-info btn-sm mb-1"
                                        data-toggle="modal"
                                        data-target="#detailModal"
                                        data-email="{{ $trx->account->email }}"
                                        data-number="{{ $trx->account->number }}"
                                        data-link="{{ $trx->account->link }}"
                                        data-product="{{ $trx->account->product->title ?? '-' }}">
                                        Lihat Akun
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-success btn-sm mb-1"
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
</div>

<!-- Modal Provide Akun -->
<div class="modal fade" id="provideModal" tabindex="-1" aria-labelledby="provideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Akun</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <ul id="detailList" class="mb-0">
                    <li><strong>Produk:</strong> <span id="detailProduct"></span></li>
                    <li id="emailField"><strong>Email:</strong> <span id="detailEmail"></span></li>
                    <li id="numberField"><strong>Nomor:</strong> <span id="detailNumber"></span></li>
                    <li id="linkField" style="display: none;"><strong>Link:</strong> <span id="detailLink"></span></li>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#transactionsTable').DataTable({
            responsive: true,
            order: [
                [3, 'desc']
            ]
        });

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
            const product = button.data('product')?.toLowerCase() || '';
            const email = button.data('email') || '-';
            const number = button.data('number') || '-';
            const link = button.data('link') || '-';

            $('#detailProduct').text(product.charAt(0).toUpperCase() + product.slice(1));

            if (product.includes('youtube') || product.includes('spotify')) {
                $('#emailField').hide();
                $('#numberField').hide();
                $('#linkField').show();
                $('#detailLink').text(link);
            } else {
                $('#emailField').show();
                $('#numberField').show();
                $('#linkField').hide();
                $('#detailEmail').text(email);
                $('#detailNumber').text(number);
            }
        });
    });
</script>
@endpush