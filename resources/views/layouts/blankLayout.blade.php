@extends('layouts/commonMaster')

@section('layoutContent')
@include('layouts.sections.navbar')

    <div class="container">
        <!-- Content -->
        @yield('content')
        <!--/ Content -->
    </div>
@endsection
