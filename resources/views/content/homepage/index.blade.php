@extends('layouts.blankHomepageLayout')

@section('title', 'Homepage')

@section('content')
<div class="container my-3">
    <div class="row justify-content-between align-items-start">
        <div class="col-md-2 text-start"><h1 class="fw-bold">KRILIN</h1></div>
        <div class="col-md-2 text-end"><a href="/login" class="btn btn-danger fw-bold">LOGIN</a></div>
    </div>

    <div class="container mx-3">
        <div class="row text-center mb-3"><h1 class="fw-bold">Mahasiswa Berprestasi</h1></div>
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
                    <tr>
                        <td>1</td>
                        <td>XXXXXXXXXXX</td>
                        <td>Muhammad Haris</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>XXXXXXXXXXX</td>
                        <td>Muhammad Maulana</td>
                        <td>95</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>XXXXXXXXXXX</td>
                        <td>Muhammad Kholiq</td>
                        <td>93</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>XXXXXXXXXXX</td>
                        <td>Muhammad Abi</td>
                        <td>90</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>XXXXXXXXXXX</td>
                        <td>Muhammad Ansyah</td>
                        <td>89</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection