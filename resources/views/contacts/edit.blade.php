@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Contact</h2>

            <form action="{{ route('contacts.update', $contact) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $contact->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Number</label>
                    <input type="text" name="number" class="form-control" value="{{ old('number', $contact->number) }}" required>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection