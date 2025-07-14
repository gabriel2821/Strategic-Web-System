@extends('layouts.app')
@section('title') <!-- Adjust title for each view -->
@section('content')

            <h1>Senarai Teras</h1>
            <a href="{{ route('teras.create') }}" class="btn btn-primary">Tambah Teras</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tajuk</th>
                        <th>Penerangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teras as $t)
                    <tr>
                        <td class="text-primary fw-bold underline">
                            <a href="{{ route('teras.detail', $t) }}">{{ $t->name }}</a>
                        </td>
                        <td>{{ $t->description }}</td>
                        <td>
                            <a href="{{ route('langkah.index', $t) }}" class="btn btn-sm btn-success">Tambah KRA</a>
                            <a href="{{ route('teras.edit', $t) }}" class="btn btn-sm btn-warning">Edit</a>
                            @if (auth()->user()->userType === 'admin')
                            <form action="{{ route('teras.destroy', $t) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti mahu memadam?')">Padam</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

@endsection



