<x-app-layout>
    <div class="text-center m-5">
        <h1 class="text-2xl font-bold">Упражнения</h1>
        <h2 class="text-gray">Здесь собраны все упражнения, из которых можно собирать тренировки</h2>
    </div>

    <div class="flex flex-col justify-center items-center">
        <a
            href="{{ route('exercises.create') }}"
            class="w-1/2 my-5 py-3 px-5 rounded-xl text-center text-xl text-white bg-emerald-500 hover:bg-emerald-700 transition-all"
        >
            Добавить новое упражнение
        </a>

        <div class="px-10 grid grid-cols-3 gap-5">
            @forelse($exercises as $exercise)
                <a href="{{ route('exercises.show', $exercise) }}" class="flex flex-col gap-5 p-5 bg-white rounded-xl shadow-sm hover:shadow-xl transition-all">
                    <div class="flex justify-between gap-5">
                        <h2 class="font-semibold">{{ $exercise->title }}</h2>
                        <span class="text-gray-500">{{ $exercise->body_part }}</span>
                    </div>
                    <p>{{ $exercise->description }}</p>
                </a>
            @empty
            @endforelse
        </div>
    </div>

    <div class="mt-5">
        {{ $exercises->links() }}
    </div>
</x-app-layout>
