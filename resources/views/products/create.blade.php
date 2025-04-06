@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Tambah Produk</h2>
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                @include('products.form', ['product' => null])
            </form>
        </div>
    </div>
</div>
@endsection