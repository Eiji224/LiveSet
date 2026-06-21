<x-app-layout>
    <div class="flex justify-center items-center">
        <div class="flex flex-col w-full p-4 gap-3">
            <div id="react-app"
                 data-exercises="{{ json_encode($exercises) }}"
                 data-userId="{{ auth()->id() }}"
                 data-trainingId="{{ $session->training_program_id }}"
                 data-liveTrainingId="{{ $session->unique_url }}"
            ></div>
        </div>
    </div>

    @viteReactRefresh
    @vite('resources/js/react/trainingProgram.jsx')
</x-app-layout>
