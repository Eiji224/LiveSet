<x-app-layout>
    <div class="text-center m-5">
        <h1 class="text-2xl font-bold">Публичные тренировки</h1>
        <h2 class="text-gray">Здесь собраны все тренировки пользователей</h2>
    </div>

    <div class="mt-5 flex flex-col gap-5 items-center">
        <a
            href="{{ route('trainings.create') }}"
            class="w-1/2 m-10 py-3 px-5 rounded-xl text-center text-xl text-white bg-sky-500 hover:bg-sky-700 transition-all"
        >
            Создать новую программу
        </a>

        @forelse($trainingPrograms as $program)
                <a
                    class="flex flex-col p-5 gap-3 bg-white rounded-xl w-1/2 hover:shadow-xl hover:ring-2 hover:ring-emerald-500 transition-all"
                    href="{{ route('trainings.show', $program) }}"
                >
                    <div class="text-center">
                        <h3 class="font-bold">{{ $program->title }}</h3>
                        <span class="text-sm text-gray-600">{{ $program->description }}</span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p>Всего упражнений: {{ $program->exercises_qty }}</p>
                        <p>Длительность тренировки: {{ gmdate('H:i:s', $program->training_time) }}</p>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p>{{ $program->user->name }}</p>
                        <div class="flex flex-row gap-3">
                            <p>Создано: {{ $program->created_at->format('d.m.Y') }}</p>
                            <p>Обновлено: {{ $program->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
        @empty
            <a class="text-lg font-bold" href="{{ route('trainings.create') }}">Пока что здесь нет тренировок. Создайте первую!</a>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $trainingPrograms->links() }}
    </div>
</x-app-layout>
