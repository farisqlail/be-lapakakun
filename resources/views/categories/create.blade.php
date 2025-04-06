@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Add Category</h2>

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection