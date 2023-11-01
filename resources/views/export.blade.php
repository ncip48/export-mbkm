<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>Hari / Tanggal</th>
            <th>Uraian Kegiatan</th>
            <th>Hasil</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $log->tanggal }}</td>
                <td>{!! nl2br($log->report) !!}</td>
                @if ($signature->paraf_mahasiswa)
                    <td class="text-center">
                        <img src="{{ $signature->paraf_mahasiswa }}" alt="signature" width="80px">
                    </td>
                    <td class="text-center">
                        <img src="{{ $signature->paraf_pembimbing }}" alt="signature" width="80px">
                    </td>
                    <td class="text-center">
                        <img src="{{ $signature->paraf_dosen }}" alt="signature" width="80px">
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
