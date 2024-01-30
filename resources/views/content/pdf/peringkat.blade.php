<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Mahasiswa Berprestasi</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan Anda */
        body {
            font-family: Arial, sans-serif;
        }
        .page {
            width: 100%;
            text-align: center;
        }
        .logo {
            margin-top: 20px;
        }
        .divider {
            border-top: 2px solid #333;
            margin: 20px 0;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="logo">
            <h1 class="h1">
                <img src="{{asset('assets/img/elements/logoundikacut.png')}}" class="img-fluid" style="max-height: 1.5em">
                KRILIN
            </h1>
        </div>
        <div class="divider"></div>
        <div class="title">
            Pemilihan Mahasiswa Berprestasi
        </div>
        <div class="card mb-5">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead class="table-dark">
                    <tr role="button">
                        <th class="text-light">Peringkat</th>
                        <th class="text-light">NIM</th>
                        <th class="text-light">Nama</th>
                        @isset($kriterias)
                            @foreach ($kriterias as $item)
                            <th class="text-light">{{$item->nama_kriteria}}</th>
                            @endforeach
                        @endisset
                        <th class="text-light">Poin</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @isset($matrix)
                        @foreach ($matrix as $index => $item)
                        <tr role="button">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->nim}}</td>
                            <td>{{$item->nama}}</td>
                            @foreach ($kriterias as $kriteria)
                                <td>{{$item->normalisasi->{$kriteria->nama_kriteria} ?? '-' }}</td>
                            @endforeach
                            <td>{{$item->poin}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr role="button">
                            <td class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
