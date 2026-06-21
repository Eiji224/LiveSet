<x-app-layout>
    <div class="flex justify-center items-center">
        <div class="flex flex-col w-full p-4 gap-3">
            <h1 class="text-center text-xl">Новая тренировка</h1>
            <div id="react-app" data-exercises="{{ json_encode($exercises) }}" data-userId="{{ auth()->id() }}"></div>
        </div>
    </div>

    @viteReactRefresh
    @vite('resources/js/react/trainingProgram.jsx')
</x-app-layout>
