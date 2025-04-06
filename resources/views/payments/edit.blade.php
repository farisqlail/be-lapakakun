@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Payment</h2>
            <form method="POST" action="{{ route('payments.update', $payment) }}">
                @csrf
                @method('PUT')
                @include('payments.form', ['payment' => $payment])
            </form>
        </div>
    </div>
</div>
@endsection