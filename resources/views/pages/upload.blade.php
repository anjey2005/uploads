@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Upload') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                            @csrf

                            <x-label-select :selects="$categories" title="{{ __('Category') }}" name="category_id" value="{{ old('category_id') }}" required></x-label-select>
                            <x-label-input title="{{ __('Title') }}" name="title" value="{{ old('title') }}" required></x-label-input>
                            <x-label-textarea title="{{ __('Description') }}" name="descr" value="{{ old('descr') }}"></x-label-textarea>
                            <x-label-input title="{{ __('File') }}" name="file" type="file" required></x-label-input>
                            <x-label-checkbox title="{{ __('Public') }}" name="public" value="{{ old('public', 'on') }}"></x-label-checkbox>
                            <x-button>{{ __('Save') }}</x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
