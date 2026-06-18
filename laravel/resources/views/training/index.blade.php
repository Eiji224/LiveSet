<x-app-layout>
    <div class="text-center m-5">
        <h1 class="text-2xl font-bold">Публичные тренировки</h1>
        <h2 class="text-gray">Здесь собраны все тренировки пользователей</h2>
    </div>

    <div class="mt-5 flex flex-col items-center">
        @forelse($trainingPrograms as $program)
            <div class="flex flex-col p-5 gap-3 w-1/2 bg-white rounded-xl cursor-pointer hover:shadow-xl hover:ring-2 hover:ring-emerald-500 transition-all">
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
            </div>
        @empty
        @endforelse
    </div>

    <div class="mt-5">
        {{ $trainingPrograms->links() }}
    </div>
</x-app-layout>
