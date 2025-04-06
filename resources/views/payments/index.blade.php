@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Payment List</h2>
            <a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">Add Payment</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>VA Number</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->bank_name }}</td>
                        <td>{{ $payment->account }}</td>
                        <td>{{ $payment->name }}</td>
                        <td>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $payment->uuid }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data pembayaran ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const uuid = button.data('uuid');
            const action = "{{ url('payments') }}/" + uuid;
            $('#deleteForm').attr('action', action);
        });
    });
</script>
@endpush