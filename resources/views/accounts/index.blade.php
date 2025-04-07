@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>List Accounts</h2>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary mb-3">+ Add Account</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="accountsTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Email</th>
                        <th>Number</th>
                        <th>Password</th>
                        <th>Due Date</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $acc)
                    <tr>
                        <td>{{ $acc->product->title ?? '-' }}</td>
                        <td>{{ $acc->email }}</td>
                        <td>{{ $acc->number }}</td>
                        <td>{{ $acc->password }}</td>
                        <td>{{ $acc->due_date }}</td>
                        <td>{{ $acc->stock }}</td>
                        <td>
                            <a href="{{ route('accounts.edit', $acc) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>
                            <button type="button" class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#deleteModal"
                                data-id="{{ $acc->id }}">
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

<!-- Modal Hapus Akun -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus akun ini?
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
        $('#accountsTable').DataTable();

        $('#deleteModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const action = `{{ url('accounts') }}/${id}`;
            $('#deleteForm').attr('action', action);
        });
    });
</script>
@endpush