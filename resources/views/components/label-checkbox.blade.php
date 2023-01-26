@props(['title', 'name', 'value'])
<div class="row mb-3">
    <div class="col-md-6 offset-md-4">
        <div class="form-check form-switch mb-4">
            <input id="{{ $name }}" name="{{ $name }}" value="true" {{ $value ? 'checked' : '' }} {!! $attributes->merge(['type' => 'checkbox', 'class' => 'form-check-input']) !!}>
            <label for="{{ $name }}" class="form-check-label">{{ $title }}</label>
        </div>
    </div>
</div>
