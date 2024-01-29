@extends('layouts/blankLayout')

@section('title', 'Kriteria')

@section('vendor-style')
    <link href="{{ url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js') }}"></script>
@endsection

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
    
    
    {{-- modal table subkriteria --}}
    <div class="modal fade" id="modalSubKriteria" data-bs-backdrop="static" tabindex="-1" style="display: none;"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Table Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                </div>
                <div class="col">
                    <div class="float-end">
                        <div id="floatingInputHelp mb-2" class="form-text" style="padding-right: 25px;">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAddSubKriteria" id="addsub_btn">Tambah</button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <table class="table dataTable" id="tabelsub">
                            <thead>
                                <tr>
                                    <th>Nama Subkriteria</th>
                                    <th>Bobot Normalisasi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="tabel_sub">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit subkriteria --}}
    <div class="modal fade" id="modalEditSubKriteria" data-bs-backdrop="static" tabindex="-1"
    style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <form action="/upsubkriteria" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3" style="display: none;">
                                <input type="text" id="idsubedit" class="form-control" name="id" placeholder="">
                            </div>
                            <div class="col mb-3" style="display: none;">
                                <input type="text" id="krisubedit" class="form-control" name="kriteria_id" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Nama SubKriteria</label>
                                <input type="text" id="name_SubKriteria" class="form-control" name="nama_subkriteria" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="row">
                                <div class="col mb-3">
                                    <label for="bobot1Basic" class="form-label">Bobot Normalisasi</label>
                                    <input type="number" step="1" min="1" id="bobot_normalisasi" name="bobot_normalisasi" class="form-control" placeholder="1">
                                </div>
                        </div>  
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="" id="editsubclose">Close</button>
                            <button class="btn btn-danger" type="submit">Save changes</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    


    {{-- modal add subkriteria --}}
    <div class="modal fade" id="modalAddSubKriteria" data-bs-backdrop="static" tabindex="-1" style="display: none;"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambah Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/insubkriteria" method="post">
                    @csrf
                    <div class="modal-body">
                    <div class="row">
                            <div class="col mb-3" style="display: none;">
                                <input type="text" id="idsub" class="form-control" name="id" placeholder="">
                            </div>
                            <div class="col mb-3" style="display: none;">
                                <input type="text" id="krisub" class="form-control" name="kriteria_id" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3" >
                            <label for="nameBasic" class="form-label">Nama SubKriteria</label>
                                <input type="text" id="nama_subkriteria" class="form-control" name="nama_subkriteria" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3" >
                            <label for="bobot1Basic" class="form-label">Bobot_Normalisasi</label>
                                <input type="number" step="1" max="5" min="1" id="bobot_normalisasi" name="bobot_normalisasi" class="form-control" placeholder="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="" id="addsubclose">Close</button>
                        <button class="btn btn-danger" type="submit">Save changes</button>
                    </div>
                </form>
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
                    </div>
                    <div class="row">
                         <div class="col mb-3">
                           <label for="bobot1Basic" class="form-label">Bobot</label>
                            <input type="number" step="0.1" max="1" min="0.1" id="bobot1Basic" name="bobot" class="form-control" placeholder="0.1">
                            </div>
                    </div>
                    <div class="row">
                                <div class="col">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger" type="submit">Save changes</button>
                </div>
            </div>
            </form>
            </div>
    </div>

    {{-- modal confirm delete subkriteria --}}
    <div class="modal fade" id="modalHapusSubKriteria" data-bs-backdrop="static" tabindex="-1"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/dessubkriteria" method="post">
                    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="backDropModalTitle">Hapus Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="col mb-3" style="display:none;" >
                            <input type="text" id="idsubdel" class="form-control" name="id" placeholder="Enter Name">
                        </div>
                <div class="modal-body">
                    <p>Are you sure to delete this??</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="" id="delsubclose">Close</button>
                    <button type="submit" class="btn btn-danger">Iya</button>
                </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Basic Bootstrap Table -->
    <div class="container">
        <div class="card mb-5">
            <div class="table-responsive text-nowrap m-4">
                <table class="table table-striped" style="width: 100%;">
                    <div>
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">No</th>
                            <th style="display: none;" scope="col">Id</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Bobot</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                            @if(isset($error))
                            @else
                                @foreach($data as $data)
                                <tr>
                                <td scope="row">{{ $loop->index + 1 }}</td>
                                <td style="display:none">{{$data->id}}</td>
                                <td>{{$data->nama_kriteria}}</td>
                                <td>{{$data->bobot}}</td>
                                <td>{{$data->periode}}</td>
                                <td>
                                    <div class="inline">
                                        <span class="subkriteria_btn text-danger" id="subkriteria_btn" data-bs-toggle="modal" data-bs-target="#modalSubKriteria"><i
                                            class="bx bx-table bx-sm me-2"></i>
                                        </span>
                                        <span class="text-success" id="update_btn" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                                class="bx bx-edit-alt bx-sm me-2"></i>
                                        </span>
                                        <span class="text-danger" id="delete_btn" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                                class="bx bx-trash bx-sm me-2"></i>
                                            </span>
                                    </div>
                                </td>
                                </tr>
                                @endforeach
                            @endif
                    </tbody>
                    </div>
                </table>

                {{-- modal confirm delete kriteria --}}
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
                                            <div class="col">
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
<script>
    //ajax
    $(document).ready(function() {
        let tableSubkriteria = $('#tabelsub').DataTable();
        // show data sub kriteria
        $('.subkriteria_btn').on('click', function() {
            // Ambil data dan set ke variabel
            var row = $(this).closest('tr');
            var kolom1 = row.find('td:nth-child(2)');
            
            // Dan set ke form update
            // Contoh: set value
            var kriteria_id = kolom1.text();
            const dataSend = {
                _token: "{{ csrf_token() }}",
                id: kriteria_id,
            };
            
            // console.log("{{csrf_token()}}");
            
            // Set value untuk kolom2, kolom3, kolom4, dan kolom5 sesuai kebutuhan
            $.ajax({
                url: "{{route('subkriteria')}}",
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify(dataSend),
                success: function(data) {
                    tableSubkriteria.clear();
                    data.datasub.forEach(element => {
                        tableSubkriteria.row.add([
                            element.nama_subkriteria,
                            element.bobot_normalisasi,
                            `
                            <div class="inline">
                            <span class="text-success" id="subupdate_btn" data-bs-toggle="modal" data-bs-target="#modalEditSubKriteria" data-subkriteriaid="${element.id}" data-kriteriaid="${element.kriteria_id}">
                            <i class="bx bx-edit-alt bx-sm me-2"></i>
                            </span>
                            <span class="text-danger" id="subdelete_btn" data-bs-toggle="modal" data-bs-target="#modalHapusSubKriteria" data-subkriteriaid="${element.id}" data-kriteriaid="${element.kriteria_id}">
                            <i class="bx bx-trash bx-sm me-2"></i>
                            </span>
                            </div>
                            `,
                        ]).draw(false)
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('ajax error',textStatus, errorThrown);
                }
            });
            
                    $('#editsubclose').on('click', function() {
                    // Menutup modal menggunakan JavaScript
                    $('#modalEditSubKriteria').modal('hide');
                    $('#modalSubKriteria').modal('show');
                    })

                    $('#delsubclose').on('click', function() {
                    // Menutup modal menggunakan JavaScript
                    $('#modalHapusSubKriteria').modal('hide');
                    $('#modalSubKriteria').modal('show');
                    }) 

                    $('#addsubclose').on('click', function() {
                    // Menutup modal menggunakan JavaScript
                    $('#modalAddSubKriteria').modal('hide');
                    $('#modalSubKriteria').modal('show');
                    }) 

        });
        
        //button delete kriteria
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


        //button delete subkriteria
        $(document).on('click', '#subdelete_btn', function() {
            const dataSend = {
                _token: "{{ csrf_token() }}",
                id: $(this).data('subkriteriaid'),
                };

                $.ajax({
                url: "{{route('upsubshow')}}",
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify(dataSend),
                success: function(data) {
                    //kebaca
                    console.log(JSON.stringify(data))
                    data.datasub.forEach(element=>{
                        document.getElementById('idsubdel').value = element.id                                 
                    })
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('ajax error',textStatus, errorThrown);
                }
            });
        });

        // button update kriteria 
        document.querySelectorAll('#update_btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Ambil data dan set ke variabel
                var row = this.closest('tr'),
                kolom1 = row.querySelector('td:nth-child(2)')
                kolom2 = row.querySelector('td:nth-child(3)')
                kolom3 = row.querySelector('td:nth-child(4)')
                kolom4 = row.querySelector('td:nth-child(5)')
                console.log(kolom1);
                // Dan set ke form update
                // Contoh: set value
                document.getElementById('idedit').value = kolom1.textContent;
                document.getElementById('nameBasic').value = kolom2.textContent;
                document.getElementById('bobotBasic').value = kolom3.textContent;
                document.getElementById('periodeBasic').value = kolom4.textContent;
                // Set value untuk kolom2, kolom3, kolom4, dan kolom5 sesuai kebutuhan
            });
        });

        $(document).on('click', '#addsub_btn', function() {
            document.getElementById('idsub').value = $('#subupdate_btn').data('subkriteriaid');
            document.getElementById('krisub').value = $('#subupdate_btn').data('kriteriaid');
            console.log(1)
        });

        // button update subkriteria 
        $(document).on('click', '#subupdate_btn', function() {
                const dataSend = {
                _token: "{{ csrf_token() }}",
                id: $(this).data('subkriteriaid'),
                };

                $.ajax({
                url: "{{route('upsubshow')}}",
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify(dataSend),
                success: function(data) {
                    //kebaca
                    console.log(JSON.stringify(data))
                    data.datasub.forEach(element=>{
                        document.getElementById('idsubedit').value = element.id
                        document.getElementById('krisubedit').value = element.kriteria_id
                        document.getElementById('name_SubKriteria').value = element.nama_subkriteria
                        document.getElementById('bobot_normalisasi').value = element.bobot_normalisasi
                                                
                    })
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('ajax error',textStatus, errorThrown);
                }
            });
        });
    });
</script>

@endsection