@extends('layouts.blankLayout')

@section('title', 'Peringkat')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="form-label">Peringkatan</h6>
                        <button class="btn btn-secondary form-control" type="submit">Start</button>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-label">Jumlah Sorting</h6>
                        <select class="form-select form-control dropdown bg-secondary text-white" aria-label="Default select example" id="jumlah_sorting">
                            <option value="1" selected>10</option>
                            <option value="2">20</option>
                            <option value="3">30</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-4 offset-5 align-self-end">
                <div class="row">
                    <div class="col offset-4">
                        <div class="float-end">
                            <button class="btn btn-secondary align-self-end">Export</button>
                            <button class="btn btn-secondary align-self-end">Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Peringkat --}}
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>IPK</th>
                    <th>SSKM</th>
                    <th>TOEFL</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                <tr>
                    <td>1</td>
                    <td>XXXXXXXXXXX</td>
                    <td>Muhammad Haris</td>
                    <td>4.0</td>
                    <td>200</td>
                    <td>500</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>XXXXXXXXXXX</td>
                    <td>Muhammad Maulana</td>
                    <td>3.9</td>
                    <td>200</td>
                    <td>510</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>XXXXXXXXXXX</td>
                    <td>Muhammad Kholiq</td>
                    <td>3.87</td>
                    <td>220</td>
                    <td>500</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>XXXXXXXXXXX</td>
                    <td>Muhammad Abi</td>
                    <td>3.85</td>
                    <td>200</td>
                    <td>500</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>XXXXXXXXXXX</td>
                    <td>Muhammad Ansyah</td>
                    <td>3.8</td>
                    <td>200</td>
                    <td>505</td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
    <!--/ Basic Bootstrap Table -->
    </div>
@endsection