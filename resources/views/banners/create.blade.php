@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Upload New Banner</h2>

            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Choice Image</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*" required>
                </div>
                <button class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection