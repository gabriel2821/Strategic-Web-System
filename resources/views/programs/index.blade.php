@extends('layouts.app')
@section('title')
@section('content')
    <a href="{{ route('teras.detail', [$langkah->teras_id, 'langkah_id' => $langkah->id]) }}" class="btn btn-secondary mb-3">Balik Teras</a>
    <h1>Urus Program untuk {{ $langkah->name }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Program Nama</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $program)
            <tr>
                <td>{{ $program->program_name }}</td>
                <td>
                    <a href="{{ route('programs.edit', [$langkah, $program]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('programs.destroy', [$langkah, $program]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti?')">Padam</button>
                        <a href="{{ route('programs.assign', [$langkah, $program]) }}" class="btn btn-sm btn-primary">Tugaskan</a>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection