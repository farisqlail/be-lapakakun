<div class="form-group">
    <label>Product</label>
    <select name="id_product" id="id_product" class="form-control" required>
        <option value="">-- Pilih Produk --</option>
        @foreach ($products as $p)
        <option value="{{ $p->id }}"
            {{ old('id_product', $account->id_product ?? '') == $p->id ? 'selected' : '' }}>
            {{ $p->title }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group" id="link-group" style="display: none;">
    <label>Link</label>
    <input type="url" name="link" class="form-control"
        value="{{ old('link', $account->link ?? '') }}" placeholder="https://example.com">
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control"
        value="{{ old('email', $account->email ?? '') }}" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="text" name="password" class="form-control"
        value="{{ old('password', $account->password ?? '') }}">
</div>

<div class="form-group">
    <label>Due Date</label>
    <input type="date" name="due_date" class="form-control"
        value="{{ old('due_date', $account->due_date ?? '') }}" required>
</div>

<div class="form-group">
    <label>Number</label>
    <input type="text" name="number" class="form-control"
        value="{{ old('number', $account->number ?? '') }}" required>
</div>

<div class="form-group">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control"
        value="{{ old('stock', $account->stock ?? '') }}" required>
</div>

<button type="submit" class="btn btn-primary">{{ isset($account) ? 'Update' : 'Save' }}</button>
<a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancel</a>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const toggleLinkInput = () => {
        const selectedText = $("#id_product option:selected").text().toLowerCase();
        if (selectedText.includes("youtube") || selectedText.includes("spotify")) {
            $("#link-group").slideDown();
        } else {
            $("#link-group").slideUp();
        }
    };

    $(document).ready(function() {
        toggleLinkInput();
        $("#id_product").change(toggleLinkInput);
    });
</script>
@endpush