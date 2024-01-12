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

    {{-- success upload --}}
    {{-- @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            This is a success dismissible alert — check it out!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}


    {{-- error filter --}}
    {{-- @if ($errors->has('jurusan'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ $errors->first('jurusan') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    @if ($errors->has('angkatan'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ $errors->first('angkatan') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <div id="your-alert-container"></div> --}}

    @include('layouts.sections.flash')

    <div class="row">
        <div class="col">
            <div class="mb-3 row align-items-center">

                <form action="{{ route('mahasiswa.filter') }}" method="GET" class="container">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="jurusan_filter">Filter by jurusan</label>
                            <select id="jurusan_filter" multiple class="form-select" name="jurusan[]">
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
                            <input class="form-control" autocomplete="off" name="angkatan" min="2020" max="{{ date('Y') }}" id="year-filter" placeholder="Masukkan tahun">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>                
                
            </div>
        </div>
        <div class="col">
            <div class="float-end">

                {{-- <div id="floatingInputHelp mb-2" class="form-text">Import excel</div>
                <button type="button" class="btn btn-danger">Import</button> --}}

                <form action="{{ route('mahasiswa.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset>
                        <small class=" mx-2">{{ __('Please upload only Excel (.xlsx or .xls) files') }}</small>
                        <div class="input-group">
                            <input type="file" required class="form-control mx-2" name="uploaded_file"
                                id="uploaded_file">
                            @if ($errors->has('uploaded_file'))
                                <p class="text-right mb-0">
                                    <small class="danger text-muted"
                                        id="file-error">{{ $errors->first('uploaded_file') }}</small>
                                </p>
                            @endif
                            <div class="input-group-append" id="button-addon2">
                                <button class="btn btn-primary square" type="submit"><i class="ft-upload mr-1"></i>
                                    Upload</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="card mb-5">
        <div class="table-responsive text-nowrap m-4">
            <table id="tabelMahasiswa" class="table table-striped dataTable" style="width: 100%;"
                aria-describedby="tabelMahasiswa_info">
                <thead>
                    <tr class="table-primary">
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="tabelMahasiswa" rowspan="1"
                            colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending"
                            style="width: 86px;">No
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Position: activate to sort column ascending" style="width: 132px;">NIM
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 66px;">Nama</th>
                        <th class="sorting" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="None: activate to sort column ascending" style="width: 66px;">Status</th>
                        @isset($kriterias)
                            @foreach ($kriterias as $item)
                            <th class="sorting" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                                aria-label="Age: activate to sort column ascending" style="width: 26px;">{{$item->nama_kriteria}}</th>
                            @endforeach
                        @endisset
                        <th class="sorting" tabindex="0" aria-controls="tabelMahasiswa" colspan="1"
                            aria-label="Salary: activate to sort column ascending" style="width: 55px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $mahasiswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama }}</td>
                            <td>{{ $mahasiswa->status }}</td>
                            @foreach ($kriterias as $kriteria)
                                <td>{{$mahasiswa->{$kriteria->nama_kriteria} ?? '-' }}</td>
                            @endforeach
                            <td>
                                <div class="inline">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <span data-id="{{ $mahasiswa->id }}" class="btnEdit dropdown-item btn-outline-warning" role="button"
                                                data-bs-toggle="modal" data-bs-target="#modalEditMhs{{$mahasiswa->id}}">
                                                <i class="bx bx-edit-alt me-2"></i>
                                                Update
                                            </span>
                                            <span data-id="{{ $mahasiswa->id }}" class="text-danger btnHapus dropdown-item btn-outline-danger" role="button"
                                                data-bs-toggle="modal" data-bs-target="#modalHapusMhs{{$mahasiswa->id}}">
                                                <i class="bx bx-trash me-2"></i>
                                                Delete
                                            </span>
                                        </div>
                                      </div>
                                    {{-- modal edit --}}
                                </div>
                            </td>
                            @include('content.mahasiswa.update')
                            @include('content.mahasiswa.delete')
                        </tr>

                    @empty
                        <p>Maaf data masih kosong!</p>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
    {{-- End Tabel Mahasiswa --}}

    {{-- {{$data->links()}} --}}

    @push('pricing-script')
        <script>
            $(document).ready(function() {

                $('#year-filter').datepicker({
                    minViewMode: 2,
                    autoclose: true,
                    startDate: "2020",
                    endDate: new Date().getFullYear().toString(),
                    format: 'yyyy'
                });
                $('#tabelMahasiswa').DataTable({
                    deferRender: true,
                });

                // const dataJurusan = [
                //     "S1 Sistem Informasi",
                //     "S1 Desain Komunikasi Visual",
                //     "S1 Teknik Komputer"
                // ];

                // Ketika tombol edit diklik
                // $('.btnEdit').click(function() {
                //     // Ambil data-id dari tombol edit mahasiswa
                //     var mahasiswaId = $(this).data('id');
                //     // console.log(mahasiswaId);

                //     // Lakukan permintaan Ajax untuk mendapatkan data mahasiswa berdasarkan ID
                //     $.ajax({
                //         url: '/mahasiswa/get-mahasiswa/' + mahasiswaId,
                //         method: 'GET',
                //         dataType: 'json', // Tentukan bahwa kita mengharapkan respons JSON
                //         success: function(data) {


                //             console.log(data);
                //             // Update konten modal dengan data yang diterima
                //             $('#nim_mhs').val(data[mahasiswaId].nim);
                //             $('#nama_mhs').val(data[mahasiswaId].nama);
                //             $('#jurusan_mhs').val(data[mahasiswaId].jurusan);
                //             $('#ipk_mhs').val(data[mahasiswaId].IPK);
                //             $('#sskm_mhs').val(data[mahasiswaId].SSKM);
                //             $('#toefl_mhs').val(data[mahasiswaId].TOEFL);
                //             if (data[mahasiswaId].karya_tulis == null || data[mahasiswaId].karya_tulis == mahasiswaId) {
                //                 $('#karya_tulis_mhs').val(0);
                //             } else {
                //                 $('#karya_tulis_mhs').val(data[mahasiswaId].karya_tulis);
                //             }
                //             // Tampilkan modal
                //             $('#modalEditMhs').modal('show');
                //         },
                //         error: function() {
                //             console.log('Gagal mengambil data mahasiswa.');
                //         }
                //     });
                // });


                // $('#btnModalEditMhs').click(function() {
                //     // JavaScript
                //     $("btnModalEditMhs").attr("disabled", true);
                //     mahasiswaId = $('#id_mhs').val();

                //     $.ajax({
                //         type: 'POST',
                //         headers: {

                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                //         },
                //         url: `/mahasiswa/update-mahasiswa/${mahasiswaId}`,
                //         data: {
                //             '_token': '{{ csrf_token() }}', // Pastikan mengirim token CSRF
                //             'nim': $('#nim_mhs').val(),
                //             'nama': $('#nama_mhs').val(),
                //             'email': $('#email_mhs').val(),
                //             'jurusan': dataJurusan[$('#jurusan_mhs').val() - 1],
                //             'ipk': $('#ipk_mhs').val(),
                //             'sskm': $('#sskm_mhs').val(),
                //             'toefl': $('#toefl_mhs').val(),
                //             'karya_tulis': $('#karya_tulis_mhs').val(),
                //             // Tambahkan data lain sesuai kebutuhan
                //         },
                //         success: function(response) {


                //             // $('#modalEditMhs').modal('hide');

                //             // Tanggapi success
                //             var alert = `
                //                             <div class="bs-toast toast toast-placement-ex m-2 fade bg-success top-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                //                                 <div class="toast-header">
                //                                     <i class="bx bx-bell me-2"></i>
                //                                     <div class="me-auto fw-medium">Briliant</div>
                //                                     <small>1 seconds ago</small>
                //                                 </div>
                //                                 <div class="toast-body">
                //                                     ${response.message}
                //                                 </div>
                //                             </div>
                //                         `;

                //             $('#your-alert-container').html(alert);

                //             setTimeout(function() {
                //                 window.location.reload()
                //             }, 1500);
                //         },
                //         error: function(error) {
                //             // Tanggapi error
                //             console.error(error);
                //             // Lakukan tindakan lainnya jika diperlukan
                //         }
                //     });



                // })


                // $('.btnHapus').click(function() {
                //     // Ambil data-id dari tombol edit mahasiswa
                //     var mahasiswaId = $(this).data('id');
                //     // console.log(mahasiswaId);

                //     // Lakukan permintaan Ajax untuk mendapatkan data mahasiswa berdasarkan ID
                //     $('#confirmHapus').click(function() {
                //         $.ajax({
                //             url: '/mahasiswa/delete/' + mahasiswaId,
                //             headers: {

                //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                //             },
                //             method: 'post',
                //             dataType: 'json',
                //             success: function(data) {

                //                 // Handle success, maybe update UI or show a message
                //                 // $('#modalHapus').modal('hide');

                //                 var alert = `
                //                             <div class="bs-toast toast toast-placement-ex m-2 fade bg-success top-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                //                                 <div class="toast-header">
                //                                     <i class="bx bx-bell me-2"></i>
                //                                     <div class="me-auto fw-medium">Briliant</div>
                //                                     <small>1 seconds ago</small>
                //                                 </div>
                //                                 <div class="toast-body">
                //                                     ${data.message}
                //                                 </div>
                //                             </div>
                //                         `;

                //                 $('#your-alert-container').html(alert);
                //                 $("#confirmHapus").hide();
                //                 $("#confirmHapus").attr("disabled", true);

                //                 setTimeout(function() {
                //                     window.location.reload()
                //                 }, 1500);

                //                 // console.log(data.message);

                //             },
                //             error: function(error) {
                //                 console.log(error.message);
                //                 // Handle errors, maybe show an error message
                //                 console.error('Error deleting mahasiswa:', error
                //                     .responseJSON);
                //             }
                //         });
                //     })
                // });




            });
        </script>
    @endpush


@endsection
