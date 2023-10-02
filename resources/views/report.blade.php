<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logbook</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        h3 {
            text-align: center;
            margin: 0px;
            text-transform: uppercase;
        }

        .identity {
            margin-bottom: 20px;
        }

        .identity tr td {
            border: none !important;
        }
    </style>
</head>

<body>
    <h3>LOGBOOK KEGIATAN</h3>
    <h3>PRAKTEK KERJA LAPANGAN TAHUN AJAR 2023/2024</h3>
    <h3>{{ $profile->pt_name }}</h3>
    <h3>PROGRAM STUDI {{ $profile->jenjang_name }} {{ $profile->prodi_name }}</h3>
    <h3 style="margin-bottom: 20px">MAGANG MBKM</h3>

    <table class="identity" style="border: none">
        <tr>
            <td>Identitas</td>
            <td>:</td>
            <td>{{ $profile->nim }} {{ $profile->name }}</td>
        </tr>
        <tr>
            <td>Nama Perusahaan</td>
            <td>:</td>
            <td>{{ $location }}</td>
        </tr>
        <tr>
            <td>Minggu Ke</td>
            <td>:</td>
            <td>{{ $minggu ?? 'Semua' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Uraian Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->tanggal }}</td>
                    <td>{{ $log->report }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
