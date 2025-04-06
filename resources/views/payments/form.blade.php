<div class="form-group">
    <label>Bank Name</label>
    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $payment->bank_name ?? '') }}" required>
</div>

<div class="form-group">
    <label>VA Number</label>
    <input type="text" name="account" class="form-control" value="{{ old('account', $payment->account ?? '') }}" required>
</div>

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $payment->name ?? '') }}" required>
</div>

<button class="btn btn-primary">Save</button>
<a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
