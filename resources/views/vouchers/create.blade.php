@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>Add Voucher</h2>

            <form action="{{ route('vouchers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Kode Voucher</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tanggal Berlaku</label>
                    <input type="date" name="valid_until" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Diskon (%)</label>
                    <input type="number" name="discount" class="form-control" required>
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection