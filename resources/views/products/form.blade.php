<div class="form-group">
    <label>Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', optional($product)->title) }}" required>
</div>

<div class="form-group">
    <label>Kategori</label>
    <select name="category_ids[]" class="form-control select2" multiple required>
        @foreach ($categories as $cat)
        <option value="{{ $cat->id }}"
            {{ isset($product) && $product->categories->contains($cat->id) ? 'selected' : '' }}>
            {{ $cat->name }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Durasi</label>
    <input type="text" name="duration" class="form-control" value="{{ old('duration', optional($product)->duration) }}" required>
</div>

<div class="form-group">
    <label>Maks. User</label>
    <input type="number" name="max_user" class="form-control" value="{{ old('max_user', optional($product)->max_user) }}" required>
</div>

<div class="form-group">
    <label>Harga</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', optional($product)->price) }}" required>
</div>

<div class="form-group">
    <label>Logo</label>
    <input type="file" name="logo" class="form-control-file">
    @if(isset($product) && $product->logo)
    <img src="{{ asset('storage/' . $product->logo) }}" width="80">
    @endif
</div>

<div class="form-group">
    <label>Banner</label>
    <input type="file" name="banner" class="form-control-file">
    @if(isset($product) && $product->banner)
    <img src="{{ asset('storage/' . $product->banner) }}" width="100">
    @endif
</div>

<div class="form-group">
    <label>Skema</label>
    <textarea name="scheme" class="form-control summernote">{{ old('scheme', optional($product)->scheme) }}</textarea>
</div>

<div class="form-group">
    <label>Informasi</label>
    <textarea name="information" class="form-control summernote">{{ old('information', optional($product)->information) }}</textarea>
</div>

<div class="form-group">
    <label>Benefit</label>
    <div id="benefit-list">
        @php
        $benefits = old('benefit', optional($product)->benefit ?? ['']);
        @endphp
        @foreach ($benefits as $key => $benefit)
        <div class="input-group mb-2">
            <input type="text" name="benefit[]" class="form-control" value="{{ $benefit }}">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-benefit">×</button>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" id="add-benefit" class="btn btn-sm btn-success">+ Tambah Benefit</button>
</div>

<button class="btn btn-primary">Simpan</button>

@push('scripts')
<!-- Summernote CDN -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<script>
    $(function() {
        $('.summernote').summernote({
            height: 150
        });

        $('#add-benefit').click(function() {
            $('#benefit-list').append(`
                <div class="input-group mb-2">
                    <input type="text" name="benefit[]" class="form-control" placeholder="Benefit...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-benefit">×</button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.remove-benefit', function() {
            $(this).closest('.input-group').remove();
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "--Pilih Kategori Produk--",
                allowClear: true
            });
        });
    });
</script>
@endpush