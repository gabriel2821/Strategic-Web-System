@extends('layouts.app')
@section('title')

@section('content')
<form action="{{ route('archives.restore', $archive) }}" method="POST" onsubmit="return confirm('Adakah anda pasti mahu memulihkan?')">
    @csrf
    <button class="btn btn-danger mb-3">Pulihkan Arkib Ini</button>
</form>

    <h1>Viewing Archive: {{ $archive->name }}</h1>

    @foreach ($archivedTeras as $teras)
    <div class="card my-3" style="width: 1500px;">
        <div class="card-header bg-primary text-white">
            {{ $teras->name }}
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $teras->description }}</p>

            @foreach ($teras->langkah as $langkah)
                <h5 class="mt-3">{{ $langkah->name }}</h5>

                <table class="table-row table-bordered table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Program Name</th>
                            <th>Inisiatif</th>
                            <th>Peneraju Utama</th>
                            <th>Tahun Mula/Siap</th>
                            <th>Petunjuk Prestasi</th>
                            <th>Pencapaian</th>
                            <th>Status</th>
                            <th>Completion (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($langkah->programs as $program)
                            @foreach ($program->programRows as $row)
                                <tr>
                                    <td>{{ $program->program_name }}</td>
                                    <td>{{ $row->inisiatif }}</td>
                                    <td>{{ $row->peneraju_utama }}</td>
                                    <td>{{ $row->tahun_mula_siap }}</td>
                                    <td>{{ $row->petunjuk_prestasi }}</td>
                                    <td>{{ $row->pencapaian }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>{{ $row->completion }}%</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endforeach
@endsection
