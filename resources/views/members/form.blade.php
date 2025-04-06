<div class="form-group">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', optional($member)->name) }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', optional($member)->email) }}" required>
</div>

<div class="form-group">
    <label>Nomor HP</label>
    <input type="text" name="number" class="form-control" value="{{ old('number', optional($member)->number) }}" required>
</div>

<div class="form-group">
    <label>Tanggal Lahir</label>
    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($member)->birth_date) }}">
</div>

<div class="form-group">
    <label>Alamat</label>
    <textarea name="address" class="form-control">{{ old('address', optional($member)->address) }}</textarea>
</div>

<div class="form-group">
    <label>Foto (opsional)</label>
    <input type="file" name="image" class="form-control-file">
    @if(isset($member) && $member->image)
        <img src="{{ asset('storage/' . $member->image) }}" width="100" class="mt-2">
    @endif
</div>

<div class="form-group">
    <label>Password {{ isset($member) ? '(kosongkan jika tidak diubah)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ isset($member) ? '' : 'required' }}>
</div>

<button class="btn btn-primary">{{ isset($member) ? 'Simpan Perubahan' : 'Simpan' }}</button>
<a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
