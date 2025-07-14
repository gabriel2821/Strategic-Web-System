@extends('layouts.app')
@section('title')
@section('content')
    <!-- Teras Details -->
    <h1>{{ $teras->name }}</h1>
    <p>{{ $teras->description }}</p>
    <hr style="border-top: 2px solid #1a237e;">

    <!-- Langkah Navigation -->
    <ul class="nav nav-tabs mb-3">
        @foreach ($teras->langkah as $langkah)
        <li class="nav-item">
            <a class="nav-link {{ request()->query('langkah_id') == $langkah->id ? 'active' : '' }}"
               href="{{ route('teras.detail', [$teras, 'langkah_id' => $langkah->id]) }}">
                {{ $langkah->name }}
            </a>
        </li>
        @endforeach
    </ul>
    <hr style="border-top: 2px solid #1a237e;">

    <!-- Program Buttons -->
    @if (request()->query('langkah_id'))
        @php
            $selectedLangkah = $teras->langkah->find(request()->query('langkah_id'));
        @endphp
        <div class="mb-3">
            <a href="{{ route('programs.create', $selectedLangkah) }}" class="btn btn-primary">Tambah Program</a>
            <a href="{{ route('programs.index', $selectedLangkah) }}" class="btn btn-primary">Urus Program</a>
        </div>

        
        <!-- Programs Table -->
        <table class="table">
            <thead>
                <tr style="background-color: #007bff; color: white;">
                    <th>Program Nama</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $program)
                <tr>
                    <td>{{ $program->program_name }}</td>
                    <td>            
                        <a href="{{ route('program_rows.index', $program) }}" class="btn btn-sm btn-success">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    
    
@endsection