@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Produk</h2>
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('products.form', ['product' => $product])
            </form>
        </div>
    </div>
</div>
@endsection