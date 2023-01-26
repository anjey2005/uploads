@props(['title', 'name'])
<div class="row mb-3">
    <label for="{{ $name }}" class="col-md-4 col-form-label text-md-end">{{ $title }}</label>
    <div class="col-md-6">
        <input id="{{ $name }}" name="{{ $name }}"
            {!! $attributes->merge(['type' => 'text', 'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) !!}>
        @if($errors->has($name))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first($name) }}</strong></span>
        @endif
    </div>
</div>
