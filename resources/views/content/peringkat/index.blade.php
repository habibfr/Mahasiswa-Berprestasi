@extends('layouts.blankLayout')

@section('title', 'Peringkat')

@section('content')
    <div class="container">
        @include('layouts.sections.flash')

        <div class="row mb-3">
            <div class="col-3">
                <form action="/peringkat" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                                <h6 class="form-label">Peringkatan</h6>
                                <button class="btn btn-secondary form-control" type="submit">Start</button>
                        </div>
                        <div class="col-md-6">
                            <h6 class="form-label">Jumlah Sorting</h6>
                            <select name="jumlah_sorting" class="form-select form-control dropdown bg-secondary text-white" aria-label="Default select example" id="jumlah_sorting">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4 offset-5 align-self-end">
                <div class="row">
                    <div class="col offset-4">
                        <div class="float-end">
                            <!-- Tombol "Export" -->
                            <form action="#" method="post" class="d-inline-block">
                                @csrf
                                <button class="btn btn-secondary align-self-end">Export</button>
                            </form>
                            <!-- Tombol "Post" dengan margin kiri -->
                            <form action="peringkat/publish" method="post" class="d-inline-block ml-2">
                                @csrf
                                <button type="submit" class="btn btn-secondary align-self-end">Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Peringkat --}}
        <!-- Basic Bootstrap Table -->
        <div class="card mb-5">
            <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    @isset($kriterias)
                        @foreach ($kriterias as $item)
                        <th>{{$item->nama_kriteria}}</th>
                        @endforeach
                    @endisset
                    <th>Poin</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @isset($matrix)
                    @foreach ($matrix as $index => $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nim}}</td>
                        <td>{{$item->nama}}</td>
                        @foreach ($kriterias as $kriteria)
                            <td>{{$item->{$kriteria->nama_kriteria} ?? '-' }}</td>
                        @endforeach
                        <td>{{$item->poin}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="text-center">
                            Tidak ada data
                        </td>
                    </tr>
                    @endisset
                </tbody>
            </table>
            </div>
        </div>
    <!--/ Basic Bootstrap Table -->
    </div>
@endsection