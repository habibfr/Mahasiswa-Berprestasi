@extends('layouts/blankLayout')

@section('title', 'Kriteria')

@section('content')
    {{-- <div class="row">
        <div class="col">
            <div class="mb-3">
                <div id="floatingInputHelp mb-2" class="form-text">Filter by jurusan dan angkatan</div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-danger dropdown-toggle px-3" data-bs-toggle="dropdown"
                        aria-expanded="false">S1 Sistem Informasi</button>
                    <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Sistem Informasi</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">D3 Sistem Informasi</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Teknik Komputer</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Desain Komunikasi Visual</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">S1 Manajemen</a></li>
                    </ul>
                    <input class="form-control mx-2 btn-outline-danger" type="number" min="2015" max="2023"
                        step="1" value="2021" id="year-filter">
                </div>
                <button type="button" class="btn btn-danger ">Filter</button>
            </div>
        </div>
        <div class="col">
            <div class="float-end">
                <div id="floatingInputHelp mb-2" class="form-text">Tambah kriteria</div>
                <button type="button" class="btn btn-danger">Tambah</button>
            </div>
        </div>
    </div> --}}

    <div class="mb-2">
        <div class="row">
            <div class="col">
                {{-- <div class="navbar-nav align-items-center"> --}}
                <div class="">
                    <div id="floatingInputHelp" class="form-text">Pencarian kriteria</div>

                    <div class="row align-items-center">
                        <div class="col">
                            <div class="card">
                                <div class="align-items-center py-1">
                                    <div class="mx-3 d-flex align-items-center ">
                                        <i class="bx bx-search fs-4 lh-0"></i>
                                        <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                                            placeholder="Search..." aria-label="Search...">
                                    </div>
                                </div>

                            </div>
                            {{-- <button type="button" class="btn btn-danger">Danger</button> --}}
                        </div>
                    </div>
                </div>
                {{-- </div> --}}
            </div>
            <div class="col">
                <div class="float-end">
                    <div id="floatingInputHelp mb-2" class="form-text">Tambah kriteria</div>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modalAddKriteria">Tambah</button>
                </div>
            </div>
        </div>

    </div>


    {{-- modal add kriteria --}}
    <div class="modal fade" id="modalAddKriteria" data-bs-backdrop="static" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Name</label>
                            <input type="text" id="nameBasic" class="form-control" placeholder="Enter Name">
                        </div>
                    <div class="row">
                         <div class="col mb-3">
                           <label for="emailBasic" class="form-label">Email</label>
                            <input type="number" step="0.1" max="1" min="0.1" id="emailBasic" class="form-control" placeholder="0.1">
                            </div>
                </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Table Basic</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>Bobot</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td>1</td>
                        <td>K001</td>
                        <td>IPK</td>
                        <td>0.4</td>
                        <td>
                            <div class="inline">
                                <span class="text-success" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                        class="bx bx-edit-alt bx-sm me-2"></i>
                                </span>
                                <span class="text-danger" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                        class="bx bx-trash bx-sm me-2"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>K002</td>
                        <td>SSKM</td>
                        <td>0.2</td>
                        <td>
                            <div class="inline">
                                <span class="text-success" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                        class="bx bx-edit-alt bx-sm me-2"></i>
                                </span>
                                <span class="text-danger" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                        class="bx bx-trash bx-sm me-2"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>K003</td>
                        <td>TOEFL</td>
                        <td>0.2</td>
                        <td>
                            <div class="inline">
                                <span class="text-success" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                        class="bx bx-edit-alt bx-sm me-2"></i>
                                </span>
                                <span class="text-danger" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                        class="bx bx-trash bx-sm me-2"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>K004</td>
                        <td>Karya Tulis</td>
                        <td>0.2</td>
                        <td>
                            <div class="inline">
                                <span class="text-success" data-bs-toggle="modal" data-bs-target="#modalEditKriteria"><i
                                        class="bx bx-edit-alt bx-sm me-2"></i>
                                </span>
                                <span class="text-danger" data-bs-toggle="modal" data-bs-target="#modalHapusKriteria"><i
                                        class="bx bx-trash bx-sm me-2"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>


            {{-- modal edit kriteria --}}
            <div class="modal fade" id="modalEditKriteria" data-bs-backdrop="static" tabindex="-1"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Edit Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameBasic" class="form-label">Name</label>
                                    <input type="text" id="nameBasic" class="form-control" placeholder="Enter Name">
                                </div>
                            
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="emailBasic" class="form-label">Email</label>
                                    <input type="number" step="0.1" max="1" min="0.1" id="emailBasic" class="form-control"
                                        placeholder="0.1">
                                </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modal confirm delete --}}
            <div class="modal fade" id="modalHapusKriteria" data-bs-backdrop="static" tabindex="-1"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="backDropModalTitle">Hapus Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure to delete this??</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Iya</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>



@endsection
