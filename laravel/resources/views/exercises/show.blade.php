<x-app-layout>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="flex flex-col p-8 gap-3 w-full max-w-md bg-white rounded-2xl shadow-lg">
            <h1 class="text-center text-2xl font-medium py-3">{{ $exercise->title }}</h1>
            <div class="flex flex-col gap-5">
                <p><span class="font-bold">Группа мышц:</span> {{ $exercise->body_part }}</p>
                <div>
                    <p class="font-bold">Описание:</p>
                    <p>{{ $exercise->description }}</p>
                </div>
                <div>
                    <p class="font-bold">Инструкция:</p>
                    <p>{{ $exercise->instruction }}</p>
                </div>

            </div>

            <form method="POST" action="{{ route('exercises.destroy', $exercise) }}" class="flex justify-between mt-10">
                @method('DELETE')
                @csrf

                <input type="submit" value="Удалить" class="px-5 py-2 rounded-xl text-red-500 cursor-pointer hover:text-red-700 transition-all" />
                <a
                    href="{{ route('exercises.edit', $exercise) }}"
                    class="px-5 py-2 text-white rounded-xl bg-sky-500 hover:bg-sky-700 cursor-pointer transition-all"
                >
                    Редактировать
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
