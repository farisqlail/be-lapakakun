@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>List Voucher</h2>
            <a href="{{ route('vouchers.create') }}" class="btn btn-primary mb-3">Add Voucher</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="vouchersTable">
                <thead>
                    <tr>
                        <th>Code/Name</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Discount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->description }}</td>
                        <td>{{ $voucher->valid_until }}</td>
                        <td>{{ $voucher->discount }}</td>
                        <td>
                            <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>

                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $voucher->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Yakin ingin menghapus voucher ini?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="submit">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#vouchersTable').DataTable();

        $('#deleteModal').on('show.bs.modal', function(event) {
            var id = $(event.relatedTarget).data('id');
            $('#deleteForm').attr('action', '/vouchers/' + id);
        });
    });
</script>
@endpush