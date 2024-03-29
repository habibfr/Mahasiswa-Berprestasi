@extends('layouts/blankHomepageLayout')

@section('title', 'Login')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <h4 class="mb-5 text-center fw-semibold">LOGIN</h4>

          {{-- Flash Login Error --}}
          @if (session()->has('loginError'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('loginError')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
          @endif
                
          <form id="formAuthentication" class="mb-3 needs-validation" action="{{url('/login')}}" method="post" novalidate>
            @csrf
            <div class="mb-3">
              <label for="text" class="form-label">NIK</label>
              <input required type="text" class="form-control" id="nik" name="nik" placeholder="Enter your NIK" autofocus>
              <div class="valid-feedback"></div>
              <div class="invalid-feedback">
                Masukkan NIK
              </div>
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input required type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <div class="valid-feedback"></div>
                <div class="invalid-feedback">
                  Masukkan Password
                </div>
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="my-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</div>
@endsection
