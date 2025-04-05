@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Edit Voucher</h2>

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('vouchers.update', $voucher) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Voucher Code/Name Voucher</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $voucher->description) }}">
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="valid_until" class="form-control"
                        value="{{ old('valid_until', \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d')) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Discount (%)</label>
                    <input type="number" name="discount" class="form-control" value="{{ old('discount', $voucher->discount) }}" required>
                </div>

                <button class="btn btn-primary">Save</button>
                <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection