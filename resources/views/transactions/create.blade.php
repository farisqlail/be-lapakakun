@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Tambah Transaksi</h2>
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                @include('transactions.form')
            </form>
        </div>
    </div>
</div>
@endsection