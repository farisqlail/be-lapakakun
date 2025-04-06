@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Member</h2>

            <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('members.form', ['member' => $member])
            </form>
        </div>
    </div>
</div>
@endsection