@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <h2>List Products</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered" id="productsTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Categories</th>
                        <th>price</th>
                        <th>Logo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>
                            @foreach ($product->categories as $cat)
                            <span class="badge badge-info">{{ $cat->name }}</span>
                            @endforeach
                        </td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->logo)
                            <img src="{{ asset('storage/' . $product->logo) }}" width="60">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen text-white"></i></a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteModal" data-uuid="{{ $product->uuid }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus produk ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let uuid = button.data('uuid');
            let action = `{{ url('products') }}/` + uuid;
            $('#deleteForm').attr('action', action);
        });
    });
</script>
@endpush