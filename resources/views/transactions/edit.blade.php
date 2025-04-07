@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Transaksi</h2>
            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PUT')
                @include('transactions.form', ['transaction' => $transaction])
            </form>
        </div>
    </div>
</div>
@endsection