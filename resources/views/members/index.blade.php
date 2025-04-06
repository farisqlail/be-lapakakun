@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Members List</h2>
            <a href="{{ route('members.create') }}" class="btn btn-primary mb-3">Add Member</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="membersTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Number</th>
                        <th>Profile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->number }}</td>
                        <td>
                            @if($member->image)
                            <img src="{{ asset('storage/' . $member->image) }}" width="50" class="rounded">
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $member->id }}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">Yakin ingin menghapus member ini?</div>
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
        $('#membersTable').DataTable();

        $('#deleteModal').on('show.bs.modal', function(event) {
            const id = $(event.relatedTarget).data('id');
            $('#deleteForm').attr('action', '/members/' + id);
        });
    });
</script>
@endpush