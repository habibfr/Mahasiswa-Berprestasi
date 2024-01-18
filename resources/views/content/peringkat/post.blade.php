<div class="modal fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-7" id="staticBackdropLabel">Posting</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="peringkat/publish" method="post" class="d-inline-block ml-2">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="jumlah_sorting" value="{{ $jumlah_sorting ?? 10 }}"> <!-- Ganti 10 dengan nilai default yang diinginkan -->
                <h5>
                    Apakah Anda yakin untuk post mahasiswa yang telah lulus pemeringkatan? 
                    <br>
                    <br>
                    Jumlah mahasiswa yang lolos pemeringkatan : {{$jumlah_sorting??10}}
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-danger align-self-end">
                    <i class='bx bx-upload me-2' id="uploadIcon"></i>
                    Post
                </button>
            </div>
        </form>
      </div>
    </div>
</div>