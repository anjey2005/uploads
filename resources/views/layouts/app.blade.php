<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    {{--    <link href="{{ asset('/build/assets/app-67dcdfd2.css') }}" rel="stylesheet">--}}
    {{--    <script src="{{ asset('/build/assets/app-7757a2cf.js') }}"></script>--}}
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="btn  {{ request()->route()->named('home') ? 'btn-outline-primary' : 'btn-primary' }}"
               href="{{ route('home') }}">{{ __('Home') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    @auth()
                        @foreach(['upload' => __('Upload new file'), 'my_uploads' => __('My file')] as $route => $name)
                            <li class="nav-item mx-2">
                                <a class="btn {{ request()->route()->named($route) ? 'btn-outline-primary' : 'btn-primary' }}"
                                   href="{{ route($route) }}">{{ $name }}</a>
                            </li>
                        @endforeach
                    @endauth
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login') && !Route::is('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if(Route::has('register') && !Route::is('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @endguest
                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('change_password') }}">{{ __('Change password') }}</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-5">
    <div id="liveToast" class="toast text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function () {

        let $fnE = (e) => {
            Msg(e.msg);
        }

        function Msg(msg) {
            $('#toastMsg').html(msg);
            (new bootstrap.Toast($('#liveToast'))).show();
        }

        @if(Auth::check())
        Echo.private('upload.{{ Auth::user()?->id ? : 0 }}').listen('.info', $fnE);
        @endif


        $('#sortBy').on('change', function (event) {
            location.href = "{{ url()->current() }}?sortBy=" + $('#sortBy').val();
        });

        // добавляем обработчик события
        $(document).ajaxError(function (event, xhr, options, thrownError) {
            Msg(thrownError);
        })

        $('a[name="like"]').on('click', function (event) {
            $.get("{{ route('like', 0) }}" + $(this).attr('id'), (e) => {
                if (e.out == 'ok') {
                    let el = $('#like_count' + $(this).attr('id'));
                    el.text(parseInt(el.text()) + 1);
                }
                $fnE(e);
            });
            return false;
        });

        $('a[name="download"]').on('click', function (event) {
            let el = $('#download_count' + $(this).attr('id'));
            el.text(parseInt(el.text()) + 1);
        });

        $('input[name="public"][id!="public"]').on('click', (event) => {
            $.get("{{ route('public', 0) }}" + $(event.target).prop('id'), {'public': $(event.target).prop('checked')}, $fnE);
        });

        $('a[name="del"]').on('click', function (event) {
            if (!confirm('{{ __("Are you sure you want to delete the file:") }} ' + $(this).attr('file'))) {
                return false;
            }
        });

        $('a[name="copyLink"]').on('mouseover', function (event) {
            $(this).attr('title', '{{ __("Click for copy link on file:") }} ' + $(this).attr('file'));
        }).on('click', function (event) {
            navigator.clipboard.writeText($(this).attr('href'));
            Msg('{{ __("Link has been copy to clipboard for file:") }} ' + $(this).attr('file'));
            return false;
        });
    })
    ;
</script>
</body>
</html>
