{{-- Update Modal --}}
<div class="modal fade" id="modalEditMhs{{$mahasiswa->id}}" data-bs-backdrop="static" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::model($data, ['method'=>'patch', 'route'=>['mahasiswa.update', $mahasiswa->id]]) !!}
            {{-- <form action="{{route('mahasiswa.update', ['id'=>$mahasiswa->id])}}" method="patch"> --}}
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelEditMhs">Edit Data Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="nim_mhs" class="form-label">NIM</label>
                                <input disabled type="text" id="nim_mhs" class="form-control" placeholder="21410100050"
                                    aria-label="Last name" required value="{{$mahasiswa->nim}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama_mhs" class="form-label">Nama</label>
                                <input type="text" id="nama_mhs" class="form-control" placeholder="Enter Name"
                                    required value="{{$mahasiswa->nama}}" disabled>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="jurusan_mhs" class="form-label">Jurusan</label>
                                <input type="text" id="nama_mhs" class="form-control" placeholder="Enter Name"
                                    required value="{{$mahasiswa->jurusan}}" disabled>
                            </div>
                        </div>

                        @isset($kriteria)
                        @foreach ($kriterias as $item)
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="{{str_replace(' ', '_', strtolower($item->nama_kriteria))}}_{{$mahasiswa->id}}" class="form-label">{{ucwords($item->nama_kriteria)}}</label>
                                    <input step="0.1" type="number" name="{{str_replace(' ', '_', strtolower($item->nama_kriteria))}}_{{$mahasiswa->id}}" id="{{str_replace(' ', '_', strtolower($item->nama_kriteria))}}_{{$mahasiswa->id}}" class="form-control" value="{{$mahasiswa->{$item->nama_kriteria} ?? 0 }}">
                                </div>
                            </div>
                        @endforeach
                        @endisset
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" id="btnModalEditMhs">Save changes</button>
                    </div>
                </div>
            {{-- </form> --}}
            {!! Form::close() !!}

        </div>
</div>