import Set from "./Set.jsx";

export default function ProgramExercise({ data, order, onUpdateSet }) {
    return (
        <div className="flex flex-col items-center p-3 gap-3 bg-white rounded-xl shadow-xl">
            <h3 className="text-center text-xl">{order}.{data.title}</h3>

            <div className="flex flex-col w-full">
                {data.sets.map((set, index) => (
                    <Set key={set.id} setData={set} index={index + 1} exerciseId={data.id} onUpdateSet={onUpdateSet} />
                ))}
            </div>
        </div>
    );
}
