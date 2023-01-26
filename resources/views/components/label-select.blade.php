@props(['title', 'name', 'value', 'selects'])
<div class="row mb-3">
    <label for="{{ $name }}" class="col-md-4 col-form-label text-md-end">{{ $title }}</label>
    <div class="col-md-6">
        <select id="{{ $name }}" name="{{ $name }}" {!! $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) !!}>
            @foreach($selects as $select)
                <option value="{{ $select->id }}"{{ $value == $select->id ? ' selected' : ''}}>{{ $select->name }}</option>
            @endforeach
                <option value="100"{{ $value == 100 ? ' selected' : ''}}>Not exist</option>
        </select>
        @if($errors->has($name))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first($name) }}</strong></span>
        @endif
    </div>
</div>
