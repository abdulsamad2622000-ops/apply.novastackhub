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
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required>
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $task->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
</div>

<div class="form-group">
    <label for="sort_order">Sort Order</label>
    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $task->sort_order ?? 0) }}">
</div>

<div class="form-group form-checkbox">
    <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $task->is_active ?? true) ? 'checked' : '' }}>
        Active (students ko dikhega)
    </label>
</div>