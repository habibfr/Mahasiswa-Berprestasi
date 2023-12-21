<nav class="mt-3 mb-5">
    <div class="container">
        <div class="col-md-12 text-center">
            <h1 class="fw-bold" style="position: relative;">
                KRILIN
                <div class="position-absolute" style="top: 0; right: 0;">
                    <form action="/" method="get">
                        <button class="btn btn-outline-danger">Logout</button>
                    </form>
                </div>
            </h1>
        </div>
        <div class="row justify-content-center">
            <h3 class="col-md-2 text-center fw-medium {{$judul=='Mahasiswa'?'navbar-active':''}}"><a href="{{ route('mahasiswa') }}">Mahasiswa</a></h3>
            <h3 class="col-md-2 text-center fw-medium {{$judul=='Kriteria'?'navbar-active':''}}"><a href="{{ route('kriteria') }}">Kriteria</a></h3>
            <h3 class="col-md-2 text-center fw-medium {{$judul=='Peringkat'?'navbar-active':''}}"><a href="{{ route('peringkat') }}">Peringkat</a></h3>
        </div>
    </div>
</nav>
