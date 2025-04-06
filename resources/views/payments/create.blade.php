@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Add Payment</h2>
            <form method="POST" action="{{ route('payments.store') }}">
                @csrf
                @include('payments.form')
            </form>
        </div>
    </div>
</div>
@endsection