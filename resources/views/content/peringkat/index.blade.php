@extends('layouts.blankLayout')

@section('title', 'Peringkat')

@section('content')
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

    <div class="container">
        @include('layouts.sections.flash')
        <div class="row mb-3">
            <div class="col-3">
                <form action="/peringkat" method="post" id="sortingForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="form-label">Jumlah Mahasiswa</h6>
                            <div class="input-group vw-50">
                                <input name="jumlah_sorting" class="form-control" placeholder="Masukkan jumlah" type="number" list="sorting" id="jumlah_sorting" onchange="document.getElementById('sortingForm').submit();">
                                <datalist id="sorting">
                                    <option value="5" {{isset($jumlah_sorting)&&$jumlah_sorting==5?'selected':''}}>5</option>
                                    <option value="10" {{isset($jumlah_sorting)&&$jumlah_sorting==10?'selected':''}}>10</option>
                                    <option value="20" {{isset($jumlah_sorting)&&$jumlah_sorting==20?'selected':''}}>20</option>
                                    <option value="30" {{isset($jumlah_sorting)&&$jumlah_sorting==30?'selected':''}}>30</option>
                                </datalist>
                            </div>
                        </div>
                    </div>
                </form>                
            </div>
            <div class="col-4 offset-5 align-self-end">
                <div class="row">
                    <div class="col offset-2">
                        <div class="float-end">
                            <!-- Tombol "Export" -->
                            {{-- <form action="{{route('generatePeringkatPDF')}}" method="get" class="d-inline-block">
                                @csrf
                                <button class="btn btn-primary align-self-end">
                                    <i class='bx bx-download me-2'></i>Unduh PDF</button>
                            </form> --}}
                            <button type="button" class="btn btn-primary d-inline-block" data-bs-toggle="modal" data-bs-target="#unduhModal">
                                <i class='bx bx-download me-2' id="unduhIcon"></i>
                                Unduh PDF
                            </button>
                            @include('content.peringkat.unduh')
                            <!-- Tombol "Post" dengan margin kiri -->
                            <button type="button" class="btn btn-danger d-inline-block ml-2" data-bs-toggle="modal" data-bs-target="#postModal">
                                <i class='bx bx-upload me-2' id="uploadIcon"></i>
                                Post
                            </button>
                            @include('content.peringkat.post')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Peringkat --}}
        <!-- Basic Bootstrap Table -->
        <div class="card mb-5">                
            <div class="card-header align-items-center text-center">
            @isset($total_bobot)
                @if ($total_bobot != 1)
                    <div class="alert alert-danger text-center" role="alert">
                        <p class="h5 text-danger fw-bold">
                            Jumlah bobot masih berjumlah {{$total_bobot+0}}! Harap tambahkan bobot supaya berjumlah 1!
                        </p>
                            Jika bobot tidak sama dengan 1 maka proses peringkat tidak dapat dimulai
                    </div>
                @endif
            @endisset
            @isset($data_sebelumnya)
                    <h3 class="fw-bold">Peringkat Mahasiswa Sebelumnya</h3>
                </div>
            @endisset
            <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                <tr role="button">
                    <th class="text-light">No</th>
                    <th class="text-light">NIM</th>
                    <th class="text-light">Nama</th>
                    @isset($kriterias)
                        @foreach ($kriterias as $item)
                        <th class="text-light">{{$item->nama_kriteria}}</th>
                        @endforeach
                    @endisset
                    <th class="text-light">Poin</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @isset($matrix)
                    @foreach ($matrix as $index => $item)
                    <tr role="button">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nim}}</td>
                        <td>{{$item->nama}}</td>
                        @foreach ($kriterias as $kriteria)
                            <td>{{$item->normalisasi->{$kriteria->nama_kriteria}+0 ?? '-' }}</td>
                        @endforeach
                        <td>{{number_format($item->poin, 3)+0}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr role="button">
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