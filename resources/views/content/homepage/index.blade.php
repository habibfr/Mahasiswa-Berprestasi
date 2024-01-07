@extends('layouts.blankHomepageLayout')

@section('title', 'Homepage')

@section('content')
<div class="container mt-3 mb-5">
    @include('layouts.sections.flash')
    <div class="row justify-content-between align-items-start">
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
    </div>

    <div class="container mx-3">
        <div class="row text-center mb-3"><h1 class="fw-bold" style="color: black">Mahasiswa Berprestasi</h1></div>
        <div class="row text-end mb-3"><div class="col"><button class="btn btn-secondary">Unduh</button></div></div>
        <div class="row">
            <div class="card">
                <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Poin</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @isset($data) 
                        @foreach ($data as $item)
                        <tr>
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
        </div>

    </div>
</div>

@endsection