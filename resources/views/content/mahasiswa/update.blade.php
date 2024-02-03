{{-- Update Modal --}}
<div class="modal fade" id="modalEditMhs" data-bs-backdrop="static" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {{-- {!! Form::model($data, ['method'=>'patch', 'route'=>['mahasiswa.update', $mahasiswa->id]]) !!} --}}
            {!! Form::open(['method'=>'patch', 'route'=>['mahasiswa.update', '__id__']]) !!}
            {{-- <form action="{{route('mahasiswa.update', ['id'=>$mahasiswa->id])}}" method="patch"> --}}
                {{-- @csrf --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelEditMhs">Edit Data Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="nim_mhs" class="form-label">NIM</label>
                                <input disabled type="text" id="nim_mhs" class="form-control" placeholder="NIM"
                                    aria-label="Last name" required value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama_mhs" class="form-label">Nama</label>
                                <input type="text" id="nama_mhs" class="form-control" placeholder="Nama"
                                    required value="" disabled>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="jurusan_mhs" class="form-label">Jurusan</label>
                                <input type="text" id="jurusan_mhs" class="form-control" placeholder="Jurusan"
                                    required value="" disabled>
                            </div>
                        </div>

                        @isset($kriterias)
                        @foreach ($kriterias as $item)
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="{{str_replace(' ', '', strtolower($item->nama_kriteria))}}_mhs" class="form-label">{{ucwords($item->nama_kriteria)}}</label><br>
                                @foreach ($subkriterias as $sub)
                                    @if ($item->id == $sub->kriteria_id)
                                        @if ((str_replace(' ', '', strtolower($item->nama_kriteria)) != str_replace(' ', '', strtolower($sub->nama_subkriteria))))
                                        <label for="{{str_replace(' ', '', strtolower($item->nama_kriteria))}}_{{$sub->id}}_mhs" class="form-label">{{ucwords($sub->nama_subkriteria)}}</label>
                                        <input step="0.01" type="number" name="{{str_replace(' ', '', strtolower($sub->nama_subkriteria))}}_{{$sub->id}}" id="{{str_replace(' ', '', strtolower($item->nama_kriteria))}}_{{$sub->id}}_mhs" class="form-control" value="">
                                        @else
                                        <input step="0.01" type="number" name="{{str_replace(' ', '', strtolower($sub->nama_subkriteria))}}_{{$sub->id}}" id="{{str_replace(' ', '', strtolower($item->nama_kriteria))}}_{{$sub->id}}_mhs" class="form-control" value="">
                                        @endif
                                    @endif
                                @endforeach
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