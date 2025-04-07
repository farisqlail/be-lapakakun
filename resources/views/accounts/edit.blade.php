@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Account</h2>
            <form method="POST" action="{{ route('accounts.update', $account) }}">
                @csrf
                @method('PUT')
                @include('accounts.form', ['account' => $account])
            </form>
        </div>
    </div>
</div>
@endsection