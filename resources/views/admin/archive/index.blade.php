<h1>Archived Cycles</h1>

<select class="form-select w-25" onchange="if(this.value) window.location.href=this.value;">
    <option selected disabled>Choose an Archive</option>
    @foreach ($archives as $archive)
        <option value="{{ route('archives.show', $archive) }}">
            {{ $archive->name }} ({{ $archive->created_at->format('Y-m-d') }})
        </option>
    @endforeach
</select>
