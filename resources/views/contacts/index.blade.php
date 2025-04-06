@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Contact List</h2>
            <a href="{{ route('contacts.create') }}" class="btn btn-primary mb-3">Add Contact</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="contactsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->number }}</td>
                        <td>
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>

                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $contact->id }}">
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">Yakin ingin menghapus kontak ini?</div>
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
        $('#contactsTable').DataTable();

        $('#deleteModal').on('show.bs.modal', function(event) {
            const id = $(event.relatedTarget).data('id');
            $('#deleteForm').attr('action', '/contacts/' + id);
        });
    });
</script>
@endpush