{{-- <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}" /> --}}

<nav class="mb-5 d-flex text-center text-white bg-dark">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            {{-- <div class="col-md-12 text-center"> --}}
            <div>
                {{-- <h1 class=""> --}}
                    <a href="/" class="float-md-left mb-0 fw-bold h1" style="text-decoration: none; color: white">
                        <img src="{{asset('assets/img/elements/logoundikacut.png')}}" class="img-fluid" style="max-height: 1.5em">
                        KRILIN
                    </a>
                {{-- </h1> --}}
                <div class="nav nav-masthead justify-content-center float-md-right">
                    {{-- <h3 class="col-md-2 text-center fw-medium {{$judul=='Mahasiswa'?'navbar-active':''}}"><a href="{{ route('mahasiswa') }}" style="color: black">Mahasiswa</a></h3>
                    <h3 class="col-md-2 text-center fw-medium {{$judul=='Kriteria'?'navbar-active':''}}"><a href="{{ route('kriteria') }}" style="color: black">Kriteria</a></h3>
                    <h3 class="col-md-2 text-center fw-medium {{$judul=='Peringkat'?'navbar-active':''}}"><a href="{{ route('peringkat') }}" style="color: black">Peringkat</a></h3> --}}
                    {{-- <h3 class="col-md-2 text-center fw-medium {{explode('/',ucwords(request()->path()))[0]=='Mahasiswa'?'navbar-active':''}}"><a href="{{ route('mahasiswa') }}" style="color: black">Mahasiswa</a></h3>
                    <h3 class="col-md-2 text-center fw-medium {{explode('/',ucwords(request()->path()))[0]=='Kriteria'?'navbar-active':''}}"><a href="{{ route('kriteria') }}" style="color: black">Kriteria</a></h3>
                    <h3 class="col-md-2 text-center fw-medium {{explode('/',ucwords(request()->path()))[0]=='Peringkat'?'navbar-active':''}}"><a href="{{ route('peringkat') }}" style="color: black">Peringkat</a></h3> --}}
                    <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Mahasiswa'?'navbar-active':''}}" href="{{ route('mahasiswa') }}" >Mahasiswa</a>
                    <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Kriteria'?'navbar-active':''}}" href="{{ route('kriteria') }}" >Kriteria</a>
                    <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Peringkat'?'navbar-active':''}}" href="{{ route('peringkat') }}" >Peringkat</a>
                </div>
            </div>
            <div class="position-absolute" style="top: 1vw; right: 1vw;">
                <form action="{{'logout'}}" method="post">
                    @csrf
                    <button class="btn btn-outline-danger">
                    <i class='bx bx-log-out me-2'></i>
                        Logout</button>
                </form>
            </div>
        </header>
    </div>
</nav>