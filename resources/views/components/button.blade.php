@props(['value'])
<div class="row mb-0">
    <div class="col-md-8 offset-md-4">
        <button {!! $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary']) !!}>{{ $slot }}</button>
    </div>
</div>
