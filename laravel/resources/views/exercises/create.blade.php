<x-app-layout>
    @if($errors->any())
        @foreach($errors->all() as $err)
            <p>{{ $err }}</p>
        @endforeach
    @endif
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="flex flex-col p-8 gap-3 w-full max-w-md bg-white rounded-2xl shadow-lg">
            <h1 class="text-center text-2xl font-medium py-3">Новое упражнение</h1>
            <form method="POST" action="{{ route('exercises.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <lable for="title" class="text-sm font-medium text-gray-700">Название упражнения</lable>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            placeholder="Название"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-sky-400 focus:ring-sky-500"
                        />
                    </div>

                    <div class="flex flex-col">
                        <lable for="body_part" class="text-sm font-medium text-gray-700">Часть тела</lable>
                        <select
                            name="body_part"
                            id="body_part"
                            class="appearance-none w-full rounded-xl bg-gray-200 border border-gray-300 py-2 px-4 pr-8 leading-tight shadow-sm hover:cursor-pointer focus:outline-none focus:border-sky-400 focus:ring-sky-500"
                        >
                            @foreach($bodyParts as $bodyPart)
                                <option value="{{ $bodyPart }}">{{ $bodyPart }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <lable id="description" class="text-sm font-medium text-gray-700">Описание упражнения</lable>
                        <textarea
                            name="description"
                            id="description"
                            placeholder="Описание"
                            rows="4"
                            class="w-full rounded-xl resize-none border-gray-300 shadow-sm focus:border-sky-400 focus:ring-sky-500"
                        ></textarea>
                    </div>

                    <div class="flex flex-col">
                        <lable id="instruction" class="text-sm font-medium text-gray-700">Инструкция к упражнению</lable>
                        <textarea
                            name="instruction"
                            id="instruction"
                            placeholder="Инструкция"
                            rows="8"
                            class="w-full rounded-xl resize-none border-gray-300 shadow-sm focus:border-sky-400 focus:ring-sky-500"
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-3">
                    <input type="submit" value="Создать" class="text-white px-5 py-2 bg-emerald-500 rounded-xl hover:bg-emerald-700 hover:cursor-pointer transition-all" />
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
