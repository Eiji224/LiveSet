


export default function ProgramExerciseControl({ onUp, onDown, onDelete }) {
    return (
        <div className="flex flex-col gap-5 bg-white p-5 rounded-xl">
            <button className="rounded-full p-2 bg-emerald-500 cursor-pointer hover:bg-emerald-700 transition-all" onClick={() => onUp()}><img src="/icons/arrow_up.svg" alt="Поднять" /></button>
            <button className="rounded-full p-2 bg-emerald-500 cursor-pointer hover:bg-emerald-700 transition-all" onClick={() => onDown()}><img src="/icons/arrow_down.svg" alt="Опустить" /></button>
            <button className="rounded-full mt-3 p-2 bg-red-500 cursor-pointer hover:bg-red-700 transition-all" onClick={() => onDelete()}><img src="/icons/close.svg" alt="Удалить" /></button>
        </div>
    );
};