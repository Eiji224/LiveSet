<x-app-layout>
    <div class="flex min-h-screen justify-center items-center">
        <div class="flex flex-col p-4 gap-3 bg-white rounded-xl shadow-xl">
            <h1 class="text-center text-xl">Новая тренировка</h1>

            <div id="react-app"></div>
        </div>
    </div>

    @viteReactRefresh
    @vite('resources/js/react/trainingProgram.jsx')
</x-app-layout>
