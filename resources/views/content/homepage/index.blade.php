@extends('layouts.blankHomepageLayout')

@section('title', 'Homepage')

@section('content')
<div class="container m-3">
    <div class="row position-relative">
        <div class="col position-absolute start-0 top-0">
            <h1 class="">KRILIN</h1>
        </div>
        <div class="col position-absolute end-0 top-0">
            <a href="login" class="btn btn-danger">Login</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h1>Mahasiswa Berprestasi</h1>
        </div>
    </div>
</div>

@endsection