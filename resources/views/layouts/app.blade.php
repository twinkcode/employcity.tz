<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Employ•city tz') }}</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
<div class="load_wrap d-none" id="loader">
    <div class="loader" style="--b: 20px;--c:#000;width:80px;--n:15;--g:7deg"></div>
</div>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand align-items-center" href="{{ url('/') }}">
                {{--                    <img class="h-100" src="{{asset('img/main-logo.png')}}" alt="Eploycity logo">--}}
                <span class="px-3 fw-semibold text-primary text-opacity-75">
                        employ<span class="text-warning">•</span>city
                    </span>
                <span class="position-relative">1 часть ТЗ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">blade</span></span>
            </a>
            <a class="position-relative btn btn-success align-items-center ms-lg-5" href="/p2">Вторая часть ТЗ <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">Vue.js</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="mt-3 mt-lg-0 collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="btn btn-outline-info nav-link mx-2" onclick="getNews()">Проверить новые</a></li>
                    <li class="nav-item"><a class="btn btn-outline-danger nav-link" onclick="baseTruncate()">Очистить базу</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
    <footer style="min-height: 100px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">CodeMaster</div>
                <div class="col-12 text-center">
                    <a href="https://t.me/codemastercode">telegram</a>
                    <span> © </span>
                    <a href="mailto:twinkcode@gmail.com">E-mail</a>
                </div>
            </div>
        </div>
    </footer>
    <button type="button" class="btn btn-secondary btn-floating btn-lg px-2 py-1 pt-0" id="btn-back-to-top">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-square" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
            <path d="M3.544 10.705A.5.5 0 0 0 4 11h8a.5.5 0 0 0 .374-.832l-4-4.5a.5.5 0 0 0-.748 0l-4 4.5a.5.5 0 0 0-.082.537z" />
        </svg>
    </button>
</div>
<style>
    #btn-back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
    }

    .loader {
        --b: 10px; /* border thickness */
        --n: 10; /* number of dashes*/
        --g: 10deg; /* gap  between dashes*/
        --c: red; /* the color */

        width: 100px; /* size */
        aspect-ratio: 1;
        border-radius: 50%;
        padding: 1px; /* get rid of bad outlines */
        background: conic-gradient(#0000, var(--c)) content-box;
        --_m: /* we use +/-1deg between colors to avoid jagged edges */ repeating-conic-gradient(#0000 0deg,
        #000 1deg calc(360deg / var(--n) - var(--g) - 1deg),
        #0000 calc(360deg / var(--n) - var(--g)) calc(360deg / var(--n))),
        radial-gradient(farthest-side, #0000 calc(98% - var(--b)), #000 calc(100% - var(--b)));
        -webkit-mask: var(--_m);
        mask: var(--_m);
        -webkit-mask-composite: destination-in;
        mask-composite: intersect;
        animation: load 1s infinite steps(var(--n));
    }

    @keyframes load {
        to {
            transform: rotate(1turn)
        }
    }

    .load_wrap {
        position: fixed;
        margin: 0;
        width: 100vw;
        height: 100vh;
        display: grid;
        place-content: center;
        align-items: center;
        grid-auto-flow: column;
        gap: 20px;
        background: rgba(255, 235, 239, 0.33);
        z-index: 1;
    }

    #app {
        background: url("{{asset('img/bg01.webp')}}") no-repeat fixed top right #e8ebeda3;
        min-height: 100vh;
    }

</style>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script>

    let scrollUpButton = document.getElementById('btn-back-to-top')

    window.onscroll = function() {
        scrollFunction()
    }

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollUpButton.style.display = 'block'
        }
        else {
            scrollUpButton.style.display = 'none'
        }
    }

    scrollUpButton.addEventListener('click', backToTop)

    function backToTop() {
        document.body.scrollTop = 0
        document.documentElement.scrollTop = 0
    }

    async function getNews() {
        let loader = document.getElementById('loader')
        loader.classList.remove('d-none')
        let response = await fetch('/rbk?count=15')
        let data = await response.json()
        if (data.length > 0) {
            alert(`Есть ${data.length} новых`)
            window.location.reload()
        }
        else { alert('Новых еще нет')}
        loader.classList.add('d-none')
    }

    async function baseTruncate() {
        let loader = document.getElementById('loader')
        loader.classList.remove('d-none')
        let response = await fetch('/truncate-base')
        // await response.text()
        window.location.reload()
    }
</script>
</body>
</html>
