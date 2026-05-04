<html>
<body>
    <form method="POST" action="{{ route('exercises.store') }}" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Название" />
        <input type="text" name="body_part" placeholder="Упражнение на какую часть тела?" />
        <textarea name="description" placeholder="Описание"></textarea>
        <textarea name="instruction" placeholder="Инструкция"></textarea>

        <input type="submit" value="Создать" />
    </form>
</body>
</html>
