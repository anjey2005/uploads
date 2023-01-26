@extends('layouts.app')

@section('content')

    <style>
        .resize {
            display: block;
            max-width: 100%;
        }

        .resizeLogo {
            max-width: 25px;
            width: auto;
            max-height: 25px;
            height: auto;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">
                        <p class="card-text">
                            <b>{{ __('Author') }}:</b> {{ $upload['user']->name }}<br>
                            <b>{{ __('Category') }}:</b> <img src="{{ url($upload['category']->logo) }}" class="resizeLogo"> {{ $upload['category']->name }}<br>
                            <b>{{ __('Title') }}:</b> {{ $upload['title'] }}<br>
                            <b>{{ __('Description') }}:</b> {{ $upload['descr'] }}<br>
                            <b>{{ __('Created') }}:</b> {{ $upload['created_at'] }}
                        </p>
                    </div>

                    <div class="card-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="btn-group">
                                        <a href="{{ route('like', $upload) }}" name="like" id="{{ $upload->id }}">
                                            <img src="{{ url('image/like.png') }}" class="resizeLogo">
                                        </a>
                                        <b>{{ __('Like') }}:</b> <span id="like_count{{ $upload->id }}">{{ $upload['likes'] }}</span>

                                        <a href="{{ route('download', $upload) }}" name="download" id="{{ $upload->id }}">
                                            <img src="{{ url('image/download.png') }}" class="resizeLogo ms-3">
                                        </a>
                                        <b>{{ __('Download') }}:</b> <span id="download_count{{ $upload->id }}">{{ $upload['uploads'] }}</span>

                                        <a href="{{ route('show', $upload) }}" name="copyLink" file="{{ $upload['file_name'] }}">
                                            <img src="{{ url('image/button_share.png') }}" class="resizeLogo ms-3">
                                        </a>
                                    </div>
                                    <small>
                                    </small>
                                </div>
                                @if(substr(mime_content_type(Storage::path('public' . DIRECTORY_SEPARATOR . $upload['file'])), 0, 5) == 'image')
                                    <img src="{{ route('view', ['upload' => $upload, 'type' => 'file', 'token' => @csrf_token()]) }}" class="resize">
                                @else
                                    <a href="{{ route('download', $upload) }}"><img src="{{ url('image/button_download.png') }}"></a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

