@if (session()->has('login_success') || session()->has('login_ended') || session()->has('published') || session('mahasiswa_deleted') || session('update_mahasiswa'))
{{-- <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('login_success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
{{-- <div aria-live="polite" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3"> --}}
    <div class="toast-container p-3 bottom-0 end-0 position-fixed" id="toastPlacement">
      <div class="bs-toast toast align-items-center 
      {{session('login_success') || session('update_mahasiswa') ? 'bg-success' : ''}}
      {{session('login_ended') || session('published') || session('mahasiswa_deleted') ? 'bg-danger' : ''}}
      border-0" id="liveToast" role="alert" aria-live="assertive">
        <div class="d-flex">
            <div class="toast-body">
              {{session('mahasiswa_deleted')}}
              {{session('update_mahasiswa')}}
              {{session('login_success')}}
              {{session('login_ended')}}
              {{session('published')}}
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
    </div>
  {{-- </div> --}}
<script>
    const toastLiveExample = document.getElementById('liveToast')
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastBootstrap.show()
</script>
@endif

{{-- @if (session()->has('login_ended'))
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<div aria-live="polite" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3">
    <div class="toast-container p-3 bottom-0 end-0 position-fixed" id="toastPlacement">
      <div class="toast" id="liveToast">
        <div class="d-flex">
            <div class="toast-body">
              {{session('login_ended')}}
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
    </div>
  </div>
<script>
    const toastLiveExample = document.getElementById('liveToast')
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastBootstrap.show()
</script>
@endif

@if (session()->has('published'))
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<div aria-live="polite" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3">
    <div class="toast-container p-3 bottom-0 end-0 position-fixed" id="toastPlacement">
      <div class="toast" id="liveToast">
        <div class="d-flex">
            <div class="toast-body">
              {{session('published')}}
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
      </div>
    </div>
  </div>
<script>
    const toastLiveExample = document.getElementById('liveToast')
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastBootstrap.show()
</script>
@endif --}}