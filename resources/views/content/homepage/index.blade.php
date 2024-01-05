@extends('layouts.blankHomepageLayout')

@section('title', 'Homepage')

@section('content')
<div class="container my-3">
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
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Hello, {{auth()->user()->name}}
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="/mahasiswa">Mahasiswa</a></li>
                  <li><a class="dropdown-item" href="/kriteria">Kriteria</a></li>
                  <li><a class="dropdown-item" href="peringkat">Peringkat</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{'logout'}}" method="post">
                        @csrf
                        <i class="bi bi-box-arrow-right"></i><button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </li>
                </ul>
            </li>
        </div>
        @else
        <div class="col-md-2 text-end"><a href="/login" class="btn btn-danger fw-bold">LOGIN</a></div>
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