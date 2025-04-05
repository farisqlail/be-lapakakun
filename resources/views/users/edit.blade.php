@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card bordder-0 shadow-lg">
        <div class="card-body">
            <h2>Edit User</h2>

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>
                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection