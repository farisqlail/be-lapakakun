@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Add Account</h2>
            <form method="POST" action="{{ route('accounts.store') }}">
                @csrf
                @include('accounts.form', ['account' => null])
            </form>
        </div>
    </div>
</div>
@endsection