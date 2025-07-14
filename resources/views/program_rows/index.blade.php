@extends('layouts.app')

@section('title', 'Maklumat Program: ' . $program->program_name)

@section('content')
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('teras.detail', [$program->langkah->teras_id, 'langkah_id' => $program->langkah->id]) }}" class="btn btn-secondary mb-3">← Balik ke Teras</a>

        <!-- Program Title -->
        <h1 class="mb-4 text-primary fw-bold">{{ $program->program_name }}</h1>

        <!-- Top Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('program_rows.create', $program) }}" class="btn btn-success">➕ Tambah Baris</a>

            @if ($program->programRows->contains(fn($row) => $row->completion < 100))
                <form action="{{ route('programs.remind', $program->id) }}" method="POST" class="d-inline">
                    @csrf
                    @if (auth()->user()->userType === 'admin')
                    <button class="btn btn-warning">📩 Hantar Peringatan</button>
                    @endif
                </form>
            @endif
        </div>

        <!-- Program Rows Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Inisiatif</th>
                        <th>Peneraju Utama</th>
                        <th>Tahun Mula/Siap</th>
                        <th>Petunjuk Prestasi</th>
                        <th>Pencapaian</th>
                        <th>Status</th>
                        <th>Selesai (%)</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($program->programRows as $row)
                        <tr>
                            <td>{{ $row->inisiatif ?? '-' }}</td>
                            <td>{{ $row->peneraju_utama ?? '-' }}</td>
                            <td>{{ $row->tahun_mula_siap ?? '-' }}</td>
                            <td>{{ $row->petunjuk_prestasi ?? '-' }}</td>
                            <td>{{ $row->pencapaian ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $row->status }}</span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $row->completion }}%;">
                                        {{ $row->completion }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('program_rows.edit', [$program, $row]) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <form action="{{ route('program_rows.destroy', [$program, $row]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti ingin memadamkan baris ini?')">Padam</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Tiada data baris program dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Status Summary Section -->
        @if ($statusSummary->isNotEmpty())
            <div class="card mt-5 shadow-sm border-info">
                <div class="card-header bg-info text-white fw-bold">📊 Ringkasan Status</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Purata Selesai (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statusSummary as $status => $data)
                                <tr>
                                    <td>
                                        {{ $status }} ({{ $data->count }})
                                    </td>
                                    <td>{{ number_format($data->average_completion, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-secondary mt-4">
                Tiada ringkasan status tersedia buat masa ini.
            </div>
        @endif
    </div>
@endsection
