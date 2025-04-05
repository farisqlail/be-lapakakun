@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Banner</h2>

            <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                </div>

                <div class="mb-3">
                    <label>Current Banner</label><br>
                    <img src="{{ asset('storage/' . $banner->image) }}" width="200">
                </div>

                <div class="mb-3">
                    <label>Choice Image (Opsional)</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                </div>

                <button class="btn btn-primary">Save</button>
                <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection