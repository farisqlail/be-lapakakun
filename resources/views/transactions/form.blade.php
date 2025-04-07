<div class="form-group">
    <label>Nama Customer</label>
    <input type="text" name="customer_name" class="form-control"
        value="{{ old('customer_name', $transaction->customer_name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Nomor Customer</label>
    <input type="text" name="customer_number" class="form-control"
        value="{{ old('customer_number', $transaction->customer_number ?? '') }}" required>
</div>

<div class="form-group">
    <label>Produk</label>
    <select name="id_product" id="id_product" class="form-control" required>
        <option value="">-- Pilih Produk --</option>
        @foreach($products as $p)
        <option value="{{ $p->id }}"
            data-price="{{ $p->price }}"
            {{ (old('id_product', $transaction->id_product ?? '') == $p->id) ? 'selected' : '' }}>
            {{ $p->title }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Kategori</label>
    <select name="id_category" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ (old('id_category', $transaction->id_category ?? '') == $cat->id) ? 'selected' : '' }}>
            {{ $cat->name }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Status Pembayaran</label>
    <select name="status_payment" class="form-control" required>
        @foreach(['pending', 'paid', 'cancel'] as $status)
        <option value="{{ $status }}"
            {{ old('status_payment', $transaction->status_payment ?? '') == $status ? 'selected' : '' }}>
            {{ ucfirst($status) }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Diskon (Rp)</label>
    <input type="number" name="discount" class="form-control" step="0.01"
        value="{{ old('discount', $transaction->discount ?? 0) }}">
</div>

<div class="form-group">
    <label>Total Harga</label>
    <input type="number" name="total_price" id="total_price" class="form-control" step="0.01"
        value="{{ old('total_price', $transaction->total_price ?? '') }}" readonly>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>

@push('scripts')
<script>
    function hitungTotal() {
        let selected = $('#id_product').find(':selected');
        let price = parseFloat(selected.data('price') || 0);
        let discount = parseFloat($('[name="discount"]').val()) || 0;

        let total = price - discount;
        total = total < 0 ? 0 : total;

        $('#total_price').val(total.toFixed(2));
    }

    $(document).ready(function() {
        $('#id_product').on('change', hitungTotal);
        $('[name="discount"]').on('input', hitungTotal);

        // Trigger awal jika form sudah ada data
        hitungTotal();
    });
</script>
@endpush