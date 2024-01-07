@if (session()->has('login_success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('login_success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session()->has('login_ended'))
{{-- <div aria-live="polite" aria-atomic="true" class="bg-body-secondary position-relative bd-example-toasts rounded-3"> --}}
    <div class="toast-container p-3 bottom-0 end-0" id="toastPlacement">
      <div class="toast" id="liveToast">
        <div class="d-flex">
            <div class="toast-body">
              {{session('login_ended')}}
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