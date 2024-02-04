@extends('layouts/blankLayout')

@section('title', explode('/',ucwords(request()->path()))[0])

@section('vendor-style')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">

    <link href="{{ url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js') }}"></script>
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js') }}">
    </script>
@endsection

@section('content')

    @include('layouts.sections.flash')

    <div class="row">
        <div class="col">
            <div class="mb-3 row align-items-center">

                <form id="filterForm" class="container">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="jurusan_filter">Filter by jurusan</label>
                            <select id="jurusanFilter" multiple class="form-select" name="jurusan[]">
                                @isset($jurusan)
                                <option value="0" selected>Pilih Jurusan</option>
                                @foreach ($jurusan as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                                @else
                                <option value="0">Tidak ada data</option>
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="angkatan">Filter by angkatan</label>
                            <input class="form-control" autocomplete="off" name="angkatan" min="2020" max="{{ date('Y') }}" id="angkatanFilter" placeholder="Masukkan tahun">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="filter-button">Filter</button>
                    <button class="btn btn-danger" id="delete-filter-button">Hapus</button>
                </form>
                
            </div>
        </div>
        <div class="col">
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="card mb-5">
        <div class="table-responsive text-nowrap m-4">
            <table id="tabelMahasiswa" class="table table-striped dataTable table-hover" style="width: 100%;"
                aria-describedby="tabelMahasiswa_info">
                <thead>
                    <tr class="table-dark">
                        <th class="sorting text-light sorting_asc" tabindex="0" aria-controls="tabelMahasiswa" rowspan="1"
                            colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending"
                            style="width: 86px;">No
                        </th>
                        <th class="sorting text-light" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Position: activate to sort column ascending" style="width: 132px;">NIM
                        </th>
                        <th class="sorting text-light" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 66px;">Nama</th>
                        <th class="sorting text-light" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="None: activate to sort column ascending" style="width: 66px;">Status</th>
                        @isset($kriterias)
                            @foreach ($kriterias as $item)
                            <th class="sorting text-light" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                                aria-label="Age: activate to sort column ascending" style="width: 26px;">{{$item->nama_kriteria}}</th>
                            @endforeach
                        @endisset
                        <th class="sorting text-light" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Salary: activate to sort column ascending" style="width: 55px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
                
            </div>
        </div>
        @include('content.mahasiswa.delete')
        @include('content.mahasiswa.update')
    {{-- End Tabel Mahasiswa --}}

    {{-- {{$data->links()}} --}}

    @push('pricing-script')
        <script>
            $(document).ready(function() {
                $('#delete-filter-button').hide()

                $('#delete-filter-button').on('click', function(event) {
                    $('#jurusanFilter').val(0)
                    $('#angkatanFilter').val('')

                    console.log('delete clicked on');
                    $('#delete-filter-button').display('none')
                    $('#filterForm').submit()
                })

                let table = $('#tabelMahasiswa').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ajax: "{{route('mahasiswa.list')}}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'nim', name: 'nim'},
                        {data: 'nama', name: 'nama'},
                        {data: 'status', name: 'status'},
                        // Tambahkan kolom dinamis sesuai dengan normalisasi
                        @foreach($kriterias as $kriteria)
                            { 
                                data: 'normalisasi.{{ $kriteria->nama_kriteria }}', 
                                name: 'normalisasi.{{ $kriteria->nama_kriteria }}',
                                render: function(data, type, full, meta) {
                                    return parseFloat(data) || 0;
                                }
                            },
                        @endforeach
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        }
                    ]
                });

                // Proses Filter Mahasiswa
                    $(document).on('submit', '#filterForm', function (event) {
                    event.preventDefault();

                    let jurusanFilter=$('#jurusanFilter').val();
                    let angkatanFilter=$('#angkatanFilter').val();
                    
                    let formData={
                        _token: "{{csrf_token()}}",
                        jurusan: jurusanFilter,
                        angkatan: angkatanFilter,
                    }

                    if (table) {
                        // Clear and destroy the DataTable
                        table.clear().destroy();

                        $('#delete-filter-button').show()

                        // Reinitialize the DataTable with new data
                        table = $('#tabelMahasiswa')
                        .DataTable({
                            // your DataTable options
                            language: {
                                emptyTable: "Tidak ada data"
                            },
                            ajax: {
                                url: "{{route('mahasiswa.filter')}}", // Assuming 'data' contains the new URL
                                type: 'POST',
                                data: formData,
                                dataType: 'json',
                                dataSrc: function (json) {
                                    $('div .dataTables_processing div').css({
                                        'display': 'none',
                                    })
                                    return json.data;
                                },
                                error: function (xhr, textStatus, errorThrown) {
                                    table.clear()
                                    console.log(xhr.status)
                                    $('div .dataTables_processing div').css({
                                        'display': 'none',
                                    })
                                    $('#tabelMahasiswa tbody').html("<tr><td colspan='10' class='text-center h4'>Data tidak dapat ditemukan atau tersedia...</td></tr>");
                                }
                            },
                            processing: true,
                            serverSide: true,
                            deferRender: true,
                            columns: [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: 'nim', name: 'nim'},
                                {data: 'nama', name: 'nama'},
                                {data: 'status', name: 'status'},
                                // Tambahkan kolom dinamis sesuai dengan normalisasi
                                @foreach($kriterias as $kriteria)
                                    { 
                                        data: 'normalisasi.{{ $kriteria->nama_kriteria }}', 
                                        name: 'normalisasi.{{ $kriteria->nama_kriteria }}',
                                        render: function(data, type, full, meta) {
                                            return parseFloat(data) || 0;
                                        }
                                    },
                                @endforeach
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: true,
                                    searchable: true
                                }
                            ]
                        });
                    }
                })


                $('#angkatanFilter').datepicker({
                    minViewMode: 2,
                    autoclose: true,
                    startDate: "{{$min_angkatan}}",
                    endDate: "{{$max_angkatan}}",
                    format: 'yyyy'
                });

                $(document).on('click', '.btnHapus', function() {
                    let mahasiswaId = $(this).data('id');
                    window.currentMahasiswaId = mahasiswaId;
                    $('#modalHapusMhs form').attr('action', function(i, val) {
                        // Ganti __id__ dengan variabel JavaScript yang menyimpan ID mahasiswa
                        return val.replace('__id__', window.currentMahasiswaId);
                    });
                });

                $(document).on('click', '.btnEdit', function () {
                    // Ambil data-id dari tombol edit mahasiswa
                    let mahasiswaId = $(this).data('id');
                    window.currentMahasiswaId = mahasiswaId;
                    // console.log(mahasiswaId);

                    // Lakukan permintaan Ajax untuk mendapatkan data mahasiswa berdasarkan ID
                    $.ajax({
                        url: "{{route('mahasiswa.modal', ['id' => '__id__']) }}".replace('__id__', mahasiswaId),
                        method: 'POST',
                        dataType: 'json', // Tentukan bahwa kita mengharapkan respons JSON
                        data: {
                            _token: "{{ csrf_token() }}", // Tambahkan token CSRF ke data permintaan
                        },
                        success: function(data) {
                            console.log(data);

                            // Update konten modal dengan data yang diterima
                            $('#nim_mhs').val(data[mahasiswaId].nim);
                            $('#nama_mhs').val(data[mahasiswaId].nama);
                            $('#jurusan_mhs').val(data[mahasiswaId].jurusan);
                            @foreach($kriterias as $kriteria)            
                                @if($subkriterias->where('kriteria_id', $kriteria->id)->isNotEmpty())
                                    // {{-- Loop through the subkriterias of the current kriteria --}}
                                    @foreach($subkriterias->where('kriteria_id', $kriteria->id) as $subkriteria)
                                            $("#{{str_replace(' ', '', strtolower($kriteria->nama_kriteria))}}_{{$subkriteria->id}}_mhs").val(parseFloat(data[mahasiswaId].nilai["{{ $subkriteria->nama_subkriteria }}"]));
                                    @endforeach
                                @endif
                            @endforeach

                            // Tampilkan modal
                            $('#modalEditMhs').modal('show');

                            // Pada bagian yang sesuai di dalam skrip JavaScript Anda
                            $('#modalEditMhs form').attr('action', function(i, val) {
                                // Ganti __id__ dengan variabel JavaScript yang menyimpan ID mahasiswa
                                return val.replace('__id__', window.currentMahasiswaId);
                            });
                        },
                        error: function() {
                            console.log('Gagal mengambil data mahasiswa.');
                        }
                    });
                });
            });
        </script>
    @endpush


@endsection
