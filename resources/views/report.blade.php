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

        td {
            /* font-size: 14px; */
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
                @if ($signature->paraf_mahasiswa)
                    <th>Paraf Mahasiswa</th>
                    <th>Paraf Pembimbing Lapangan</th>
                    <th>Paraf Dosen Pembimbing</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->tanggal }}</td>
                    <td>{{ $log->report }}</td>
                    @if ($signature->paraf_mahasiswa)
                        <td>
                            <img src="{{ $signature->paraf_mahasiswa }}" alt="signature" width="80px">
                        </td>
                        <td>
                            <img src="{{ $signature->paraf_pembimbing }}" alt="signature" width="80px">
                        </td>
                        <td>
                            <img src="{{ $signature->paraf_dosen }}" alt="signature" width="80px">
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
