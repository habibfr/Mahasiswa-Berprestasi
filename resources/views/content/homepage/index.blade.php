@extends('layouts.blankHomepageLayout')

@section('title', 'Homepage')

@section('content')
<div class="mb-5">
    @include('layouts.sections.flash')

    <nav class="mb-5 pb-3 pt-3 d-flex text-center text-white bg-dark">
        {{-- <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div> --}}
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center w-100">
                    <div class="col-md-2 text-start">
                        <a href="/" class="float-md-left mb-0 fw-bold h1" style="text-decoration: none; color: white">
                            <img src="{{asset('assets/img/elements/logoundikacut.png')}}" class="img-fluid" style="max-height: 1.5em">
                            KRILIN
                        </a>
                    </div>
                    {{-- <div class="nav nav-masthead justify-content-center float-md-right">
                        <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Mahasiswa'?'navbar-active':''}}" href="{{ route('mahasiswa') }}" >Mahasiswa</a>
                        <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Kriteria'?'navbar-active':''}}" href="{{ route('kriteria') }}" >Kriteria</a>
                        <a class="nav-link admin h4 {{explode('/',ucwords(request()->path()))[0]=='Peringkat'?'navbar-active':''}}" href="{{ route('peringkat') }}" >Peringkat</a>
                    </div> --}}
                    <div class="col-md-2 text-end">
                        @auth
                        {{-- <div class="position-absolute text-start" style="top: 1vw; right: 1vw"> --}}
                        <div class="position-relative">
                            <li class="nav dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 20vw;">
                                    <i class='bx bxs-user-rectangle me-1'></i>
                                  Hello, {{auth()->user()->name}}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li>
                                    <a class="dropdown-item" href="/mahasiswa">
                                        <i class='bx bxs-group me-2 bx-tada'></i>
                                        Mahasiswa
                                    </a>
                                  </li>
                                  <li><a class="dropdown-item" href="/kriteria">
                                    <i class='bx bx-target-lock me-2  bx-tada'></i>Kriteria</a></li>
                                  <li><a class="dropdown-item" href="peringkat">
                                    <i class='bx bx-trophy me-2  bx-tada'></i>Peringkat</a></li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li>
                                    <form action="{{'logout'}}" method="post">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class='bx bx-log-out me-2 bx-fade-left'></i>
                                            Logout
                                        </button>
                                    </form>
                                  </li>
                                </ul>
                            </li>
                        </div>
                        {{-- </div> --}}
                        @else
                        <div class="">
                            <a href="/login" class="btn btn-danger fw-bold d-flex align-items-center">
                                <i class='bx bx-log-in me-2'></i>
                                <span>LOGIN</span>
                            </a>
                        </div>
                        @endauth
                    </div>
                    </div>
                </div>
                {{-- </div>
            </header>
        </div> --}}
    </nav>
    {{-- <div class="row justify-content-between align-items-start">
        <div class="col-md-2 text-start">
            <a href="/" style="text-decoration: none;">
                <h1 class="fw-bold" style="color: black">
                    KRILIN
                </h1>
            </a>
        </div>
        @auth
        <div class="col-md-2 text-start">
            <li class="nav dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 20vw;">
                  Hello, {{auth()->user()->name}}
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="/mahasiswa">
                    <i class='bx bxs-group me-2'></i>Mahasiswa</a></li>
                  <li><a class="dropdown-item" href="/kriteria">
                    <i class='bx bx-target-lock me-2'></i>Kriteria</a></li>
                  <li><a class="dropdown-item" href="peringkat">
                    <i class='bx bx-trophy me-2'></i>Peringkat</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{'logout'}}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class='bx bx-log-out me-2'></i>
                            Logout
                        </button>
                    </form>
                  </li>
                </ul>
            </li>
        </div>
        @else
        <div class="col-md-2 text-end">
            <a href="/login" class="btn btn-danger fw-bold d-flex align-items-center">
                <i class='bx bx-log-in me-2'></i>
                <span>LOGIN</span>
            </a>
        </div>
        @endauth
    </div> --}}

    <main class="container">
        <div class="row text-center mb-3"><h1 class="fw-bold" style="color: black">Mahasiswa Berprestasi</h1></div>
        <div class="row text-end mb-3"><div class="col"><button class="btn btn-secondary">Unduh</button></div></div>
        <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
            <thead class="table-dark">
                <tr role="button">
                <th class="text-light">No</th>
                <th class="text-light">NIM</th>
                <th class="text-light">Nama</th>
                <th class="text-light">Poin</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @isset($data)
                @foreach ($data as $item)
                <tr role="button">
                <td>{{$item->peringkat}}</td>
                <td>{{$item->nim}}</td>
                <td>{{$item->nama}}</td>
                <td>{{$item->poin}}</td>
                </tr>
                @endforeach
                @endisset
            </tbody>
            </table>
        </div>
        </div>
    </main>
</div>

@endsection