    {{-- modal confirm delete --}}
    <div class="modal fade" id="modalHapusMhs{{$mahasiswa->id}}" data-bs-backdrop="static" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::model($data, ['method'=>'delete', 'route'=>['mahasiswa.delete', $mahasiswa->id]]) !!}
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h2 class="modal-title fw-bold text-danger" id="backDropModalTitle">Hapus Mahasiswa</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda ingin mendelete data mahasiswa ini??</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="confirmHapus" class="btn btn-outline-danger">Iya</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>