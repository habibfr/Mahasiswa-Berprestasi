<div class="modal fade" id="unduhModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-7 text-danger text-uppercase fw-bold" id="staticBackdropLabel">warning!</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{route('generatePeringkatPDF')}}" method="get" class="d-inline-block">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="jumlah_sorting" value="{{ $jumlah_sorting ?? null }}"> <!-- Ganti 10 dengan nilai default yang diinginkan -->
                <h5>
                    @isset($jumlah_sorting)
                    Anda mempunyai proses seleksi yang belum di-simpan atau post. Apakah Anda yakin ingin melanjutkan?
                    <br>
                    <br>
                    Jumlah mahasiswa yang telah diproses : {{$jumlah_sorting}} mahasiswa
                    @else
                    Anda akan mengunduh proses yang telah dijalankan sebelumnya. Apakah Anda yakin ingin melanjutkan?
                    @endisset
                    <br>
                    <br>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-danger align-self-end">
                    <i class='bx bx-download me-2' id="unduhIcon"></i>
                    Lanjutkan
                </button>
            </div>
        </form>
      </div>
    </div>
</div>