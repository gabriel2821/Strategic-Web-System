@extends('layouts.app')
@section('title', 'Assign User to Program')

@section('content')
    <h2>Assign User to Program: {{ $program->program_name }}</h2>

    <form action="{{ route('programs.assign.store', [$langkah->id, $program->id]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $program->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign User</button>
        <a href="{{ route('programs.index', $langkah->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
