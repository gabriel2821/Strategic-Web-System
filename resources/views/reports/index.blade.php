@extends('layouts.app')
@section('title')
@section('content')
    <h1>Laporan</h1>
    <hr style="border-top: 2px solid #1a237e;">

    <!-- Filter and Search Form -->
    <form action="{{ route('program_rows.report') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="teras">Teras</label>
                <input type="text" name="teras" class="form-control" value="{{ request('teras') }}" placeholder="Teras Nama" onchange="this.form.submit()">
            </div>
            <div class="col-md-2">
                <label for="month">Bulan</label>
                <select name="month" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label for="year">Tahun</label>
                <select name="year" class="form-control" onchange="this.form.submit()">
                    <option value="">All</option>
                    @for ($i = 2020; $i <= 2030; $i++)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label for="status">Status</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="Belum Mula" {{ request('status') == 'Belum Mula' ? 'selected' : '' }}>Belum Mula</option>
                    <option value="Dalam Pelaksanaan" {{ request('status') == 'Dalam Pelaksanaan' ? 'selected' : '' }}>Dalam Pelaksanaan</option>
                    <option value="Projek Sakit" {{ request('status') == 'Projek Sakit' ? 'selected' : '' }}>Projek Sakit</option>
                    <option value="Ditangguh" {{ request('status') == 'Ditangguh' ? 'selected' : '' }}>Ditangguh</option>
                    <option value="Digugurkan" {{ request('status') == 'Digugurkan' ? 'selected' : '' }}>Digugurkan</option>
                    <option value="Tercapai" {{ request('status') == 'Tercapai' ? 'selected' : '' }}>Tercapai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search">Program</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Program Nama" onchange="this.form.submit()">
            </div>
        </div>
    </form>

    <!-- Programs and Program Rows Tables by Teras -->
    <div id="print-area">
    @foreach ($terasPrograms as $terasId => $data)
        @if ($data['programs']->isNotEmpty())
            <h2 class="mt-4">{{ $data['teras']->name ?? 'Unknown Teras' }}</h2>
            <table class="table-row table-bordered">
                <thead>
                    <tr style="background-color: #007bff; color: white;">
                        <th>Langkah</th>
                        <th>Program Name</th>
                        <th>Inisiatif</th>
                        <th>Peneraju Utama</th>
                        <th>Tahun Mula/Siap</th>
                        <th>Petunjuk Prestasi</th>
                        <th>Pencapaian</th>
                        <th>Status</th>
                        <th>Completion(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['programs'] as $program)
                        @foreach ($program->programRows as $row)
                        <tr>
                            <td>{{ $program->langkah->name ?? '-' }}</td>
                            <td>{{ $program->program_name }}</td>
                            <td>{{ $row->inisiatif ?? '-' }}</td>
                            <td>{{ $row->peneraju_utama ?? '-' }}</td>
                            <td>{{ $row->tahun_mula_siap ?? '-' }}</td>
                            <td>{{ $row->petunjuk_prestasi ?? '-' }}</td>
                            <td>{{ $row->pencapaian ?? '-' }}</td>
                            <td>{{ $row->status ?? '-' }}</td>
                            <td>{{ $row->completion ?? '0' }}%</td>
                        </tr>
                        @endforeach
                        @if ($program->programRows->isEmpty())
                            <tr>
                                <td>{{ $program->langkah->name ?? '-' }}</td>
                                <td>{{ $program->program_name }}</td>
                                <td colspan="7">Tiada baris tersedia</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="btn btn-primary mt-3 no-print">Print Report</button>

    <!-- Debug Information -->
    <p>Bilangan Kumpulan Teras: {{ count($terasPrograms) }}</p>
@endsection