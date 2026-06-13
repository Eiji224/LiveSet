<html>
<body>
    <h1>{{ $exercise->title }}</h1>
    <p>{{ $exercise->body_part }}</p>
    <p>{{ $exercise->description }}</p>
    <p>{{ $exercise->instruction }}</p>

    <form method="POST" action="{{ route('exercises.destroy', $exercise) }}">
        @method('DELETE')
        @csrf

        <input type="submit" value="Удалить" />
    </form>
</body>
</html>
