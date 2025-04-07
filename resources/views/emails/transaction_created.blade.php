<h2>Hai {{ $transaction->customer_name }},</h2>
<p>Terima kasih, transaksi Anda telah berhasil dibuat dengan detail berikut:</p>

<ul>
    <li><strong>Kode Transaksi:</strong> {{ $transaction->transaction_code }}</li>
    <li><strong>Produk:</strong> {{ $transaction->product->title ?? '-' }}</li>
    <li><strong>Kategori:</strong> {{ $transaction->category->name ?? '-' }}</li>
    <li><strong>Total Harga:</strong> Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</li>
</ul>

<p>Status Pembayaran: <strong>{{ ucfirst($transaction->status_payment) }}</strong></p>

<p>Salam,<br>Tim Admin</p>
