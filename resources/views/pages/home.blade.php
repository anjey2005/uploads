@extends('layouts.app')

@section('content')

    <style>
        .resizePreview {
            display: block;
            max-width: 100%;
            margin: 3px;
        }

        .resizeLogo {
            max-width: 25px;
            margin-left: 5px;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                @foreach($categories as $category)
                                    <a href="{{ route(request()->route()->getName(), ['category' => $category['id'] ]) }}"
                                       class="btn btn-primary">{{ $category['name'] }}</a>
                                @endforeach
                            </div>
                            <label for="sortBy" class="col-md-4 col-form-label text-md-end">{{ __('Order by:') }}</label>
                            <div class="col-md-2">
                                <select id="sortBy" class="form-control">
                                    @foreach(['date' => __('Date'), 'like' => __('Like'), 'download' => __('Download'), ] as $n => $v)
                                        <option value="{{ $n }}"{{ request('sortBy', 'date') == $n ? ' selected' : ''}}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                            @foreach($uploads as $upload)
                                <div class="col">
                                    <div class="card shadow-sm">
                                        <a href="{{ route('show', $upload) }}">
                                            <img src="{{ route('view', $upload) }}" class="resizePreview" title="{{ $upload['file_name'] }}">
                                        </a>
                                        <div class="card-body">
                                            <p class="card-text">
                                                <img src="{{ url($upload['category']->logo) }}" class="resizeLogo"
                                                     title="{{ $upload['category']->name }}">
                                                {{ $upload['title'] }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">

                                                <div class="btn-group">

                                                    <a href="{{ route('like', $upload) }}" name="like" id="{{ $upload->id }}">
                                                        <img src="{{ url('image/like.png') }}" class="resizeLogo">
                                                    </a>
                                                    <span id="like_count{{ $upload->id }}">{{ $upload['likes'] }}</span>

                                                    <a href="{{ route('download', $upload) }}" name="download" id="{{ $upload->id }}">
                                                        <img src="{{ url('image/download.png') }}" class="resizeLogo">
                                                    </a>
                                                    <span id="download_count{{ $upload->id }}">{{ $upload['uploads'] }}</span>

                                                    <a href="{{ route('show', $upload) }}" name="copyLink" file="{{ $upload['file_name'] }}">
                                                        <img src="{{ url('image/button_share.png') }}" class="resizeLogo">
                                                    </a>

                                                </div>
                                                <small class="text-muted">
                                                    {{ date_format($upload['created_at'], "Y-m-d") }}
                                                    @if(request()->route()->getName() == 'my_uploads')
                                                        <div class="form-check form-switch mb-4">
                                                            <input class="form-check-input" type="checkbox" role="switch" value="on"
                                                                   id="{{ $upload['id'] }}" name="public"
                                                                {{ $upload['public'] ? ' checked' : '' }}>
                                                            <label class="form-check-label" for="ch{{ $upload['id'] }}">Public</label>

                                                            <a href="{{ route('delete', $upload) }}" name="del" title="{{ __('Del file') }}"
                                                               file="{{ $upload['file_name'] }}">
                                                                <img src="{{ url('image/button_delete.png') }}" class="resizeLogo">
                                                            </a>
                                                        </div>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-footer">
                        {{ $uploads->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
