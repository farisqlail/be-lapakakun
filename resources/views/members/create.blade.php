@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Tambah Member</h2>

            <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('members.form', ['member' => null])
            </form>
        </div>
    </div>
</div>
@endsection