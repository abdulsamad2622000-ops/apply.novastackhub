@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label for="certificate_number">Certificate Number</label>
    <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $certificate->certificate_number ?? $certificate_number_default ?? '') }}" required>
</div>

<div class="form-group">
    <label for="full_name">Full Name</label>
    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $certificate->full_name ?? '') }}" required>
</div>

<div class="form-group">
    <label for="title">Title (e.g. Web Development Internship)</label>
    <input type="text" id="title" name="title" value="{{ old('title', $certificate->title ?? '') }}" required>
</div>

<div class="form-group">
    <label for="issue_date">Issue Date</label>
    <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', isset($certificate) && $certificate->issue_date ? $certificate->issue_date->format('Y-m-d') : date('Y-m-d')) }}" required>
</div>

<div class="form-group">
    <label for="completion_date">Completion Date (optional)</label>
    <input type="date" id="completion_date" name="completion_date" value="{{ old('completion_date', isset($certificate) && $certificate->completion_date ? $certificate->completion_date->format('Y-m-d') : '') }}">
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select id="status" name="status" required>
        <option value="valid" {{ old('status', $certificate->status ?? 'valid') === 'valid' ? 'selected' : '' }}>Valid</option>
        <option value="revoked" {{ old('status', $certificate->status ?? '') === 'revoked' ? 'selected' : '' }}>Revoked</option>
    </select>
</div>

<div class="form-group">
    <label for="notes">Notes (optional, internal only)</label>
    <textarea id="notes" name="notes" rows="3">{{ old('notes', $certificate->notes ?? '') }}</textarea>
</div>