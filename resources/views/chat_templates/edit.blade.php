@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Template Chat</h2>

            <form action="{{ route('chat-templates.update', $chatTemplate) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $chatTemplate->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control summernote" required>{{ old('description', $chatTemplate->description) }}</textarea>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- CDN Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
@endpush