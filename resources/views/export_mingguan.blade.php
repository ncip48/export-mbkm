<table>
    <thead>
        <tr>
            <th>Minggu</th>
            <th>Uraian Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $log)
            <tr>
                <td>{{ $log->minggu }}</td>
                <td>{!! nl2br($log->report) !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
