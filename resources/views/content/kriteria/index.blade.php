@extends('layouts/blankLayout')

@section('title', 'Kriteria')

@section('content')
@include('layouts.sections.flash')
    {{-- <div class="row">
        <div class="col">
            <div class="mb-3">
                <div id="floatingInputHelp mb-2" class="form-text">Filter by jurusan dan angkatan</div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-danger dropdown-toggle px-3" data-bs-toggle="dropdown"
                        aria-expanded="false">S1 Sistem Informasi</button>
                    <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Sistem Informasi</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">D3 Sistem Informasi</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Teknik Komputer</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Desain Komunikasi Visual</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Manajemen</a></li>
                    </ul>
                    <input class="form-control mx-2 btn-outline-danger" type="number" min="2015" max="2023"
                        step="1" value="2021" id="year-filter">
                </div>
                <button type="button" class="btn btn-danger ">Filter</button>
            </div>
        </div>
        <div class="col">
            <div class="float-end">
                <div id="floatingInputHelp mb-2" class="form-text"></div>
                <button type="button" class="btn btn-danger">Tambah</button>
            </div>
        </div>
    </div> --}}

    <div class="mb-2">
        <div class="row">
            <div class="col">
                <div class="float-end">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modalAddKriteria">Tambah</button>
                </div>
            </div>
        </div>

    </div>


    {{-- modal add kriteria --}}
    <div class="modal fade" id="modalAddKriteria" data-bs-backdrop="static" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/inkriteria" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama Kriteria</label>
                            <input type="text" id="name1Basic" class="form-control" name="nama_kriteria" placeholder="Enter Name">
                        </div>
                    <div class="row">
                         <div class="col mb-3">
                           <label for="bobot1Basic" class="form-label">Bobot</label>
                            <input type="number" step="0.1" max="1" min="0.1" id="bobot1Basic" name="bobot" class="form-control" placeholder="0.1">
                            </div>
                    </div>
                    <div class="row">
                                <div class="col mb-3">
                                    <label for="tipeBasic" class="form-label">Tipe</label>
                                </div>
                            <div class="row">
                                <div class="col mb-3">
                                <div class="form-check">
                                        <input class="form-check-input" type="radio" name="atribut" id="exampleRadios1" name="benefit" value="benefit" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Benefit
                                        </label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="atribut" id="exampleRadios2" value="cost" name="cost">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Cost
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                
                    </div>

                    <div class="row">
                         <div class="col mb-3">
                           <label for="periode1Basic" class="form-label">Periode</label>
                            <input name="periode" type="number" id="periode1Basic" class="form-control" min="2000">
                            </div>
                </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger" type="submit">Save changes</button>
                </div>
            </div>
            </form>
        </div>
    </div>



    <!-- Basic Bootstrap Table -->
    <div class="card mb-5">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="display: none;">Id</th>
                        <th>Nama</th>
                        <th>Bobot</th>
                        <th>Periode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    
                        @isset($data)
                        @foreach($data as $data)
                        <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td style="display:none">{{$data->id}}</td>
                        <td>{{$data->nama_kriteria}}</td>
                        <td>{{$data->bobot}}</td>
                        <td>{{$data->periode}}</td>
                        <td>
                            <div class="inline">
                                <span class="text-success" id="update_btn" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                        class="bx bx-edit-alt bx-sm me-2"></i>
                                </span>
                                <span class="text-danger" id="delete_btn" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                        class="bx bx-trash bx-sm me-2"></i>
                                    </a>
                            </div>
                        </td>
                        <tr>
                        @endforeach
                        @endisset
                </tbody>
            </table>

            {{-- modal confirm delete --}}
            <div class="modal fade" id="modalHapusKriteria" data-bs-backdrop="static" tabindex="-1"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/deskriteria" method="post">
                            @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="backDropModalTitle">Hapus Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="col mb-3" style="display: none;" >
                                    <input type="text" id="id" class="form-control" name="id" placeholder="Enter Name">
                                </div>
                        <div class="modal-body">
                            <p>Are you sure to delete this??</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Iya</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- modal edit kriteria --}}
            <div class="modal fade" id="modalEditKriteria" data-bs-backdrop="static" tabindex="-1"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Edit Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="/upkriteria" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3" style="display: none;">
                                    <input type="text" id="idedit" class="form-control" name="id" placeholder="Enter Name">
                                </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameBasic" class="form-label">Nama Kriteria</label>
                                    <input type="text" id="nameBasic" class="form-control" name="nama_kriteria" placeholder="Enter Name">
                                </div>
                            <div class="row">
                                <div class="col mb-3">
                                <label for="bobot1Basic" class="form-label">Bobot</label>
                                    <input type="number" step="0.1" max="1" min="0.1" id="bobotBasic" name="bobot" class="form-control" placeholder="0.1">
                                    </div>
                            </div>
                            <div class="row">
                                        <div class="col mb-3">
                                            <label for="tipeBasic" class="form-label">Tipe</label>
                                        </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                        <div class="form-check">
                                                <input class="form-check-input" type="radio" name="atribut" id="exampleRadios1" name="Benefit" value="benefit" checked>
                                                <label class="form-check-label" for="exampleRadios1">
                                                    Benefit
                                                </label>
                                        </div>
                                        <div class="form-check">
                                                <input class="form-check-input" type="radio" name="atribut" id="exampleRadios2" value="Cost" name="cost">
                                                <label class="form-check-label" for="exampleRadios2">
                                                    Cost
                                                </label>
                                        </div>
                                                
                                    </div>
                            </div>
                                        

                            <div class="row">
                                <div class="col mb-3">
                                <label for="periode1Basic" class="form-label">Periode</label>
                                    <input name="periode" type="number" id="periodeBasic" class="form-control" min="2000">
                                </div>
                            </div>
                        
                        </div>
                            
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-danger" type="submit">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    </div>
    
<script>
    //button delete
    document.querySelectorAll('#delete_btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Ambil data dan set ke variabel
        var row = this.closest('tr'),
            kolom1 = row.querySelector('td:nth-child(2)')
        // Dan set ke form update
        // Contoh: set value
        document.getElementById('id').value = kolom1.textContent;
        // Set value untuk kolom2, kolom3, kolom4, dan kolom5 sesuai kebutuhan
    });
    });
    // button update
    document.querySelectorAll('#update_btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Ambil data dan set ke variabel
        var row = this.closest('tr'),
            kolom1 = row.querySelector('td:nth-child(2)')
            console.log(kolom1)
            kolom2 = row.querySelector('td:nth-child(3)')
            kolom3 = row.querySelector('td:nth-child(4)')
            kolom4 = row.querySelector('td:nth-child(5)')
        // Dan set ke form update
        // Contoh: set value
        document.getElementById('idedit').value = kolom1.textContent;
        document.getElementById('nameBasic').value = kolom2.textContent;
        document.getElementById('bobotBasic').value = kolom3.textContent;
        document.getElementById('periodeBasic').value = kolom4.textContent;
        // Set value untuk kolom2, kolom3, kolom4, dan kolom5 sesuai kebutuhan
    });
  });
    
</script>

@endsection