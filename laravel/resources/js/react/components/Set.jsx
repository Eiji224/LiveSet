import { useEffect, useState } from "react";
import Timer from "./Timer";

export default function Set({ setData, index, exerciseId, onUpdateSet }) {
    const [weight, setWeight] = useState(setData.weight ? setData.weight : "1");
    const [reps, setReps] = useState(setData.reps ? setData.reps : "1");

    useEffect(() => {
        onUpdateSet(exerciseId, setData.id, 'weight', weight);
        onUpdateSet(exerciseId, setData.id, 'reps', reps);
    }, [weight, reps]);

    return (
        <div className="flex flex-row justify-center items-center w-full gap-3 p-4">
            <span>{index}</span>
            <div className="flex flex-col gap-3 flex-1">
                <div className="flex justify-between px-10">
                    <div>
                        <label>Вес: </label>
                        <input
                            type="number"
                            value={weight}
                            min="1"
                            onChange={e => setWeight(Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16"
                        /> кг
                    </div>

                    <div>
                        <label>Подходы: </label>
                        <input
                            type="number"
                            value={reps}
                            min="1"
                            onChange={e => setReps(Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16" />
                    </div>
                </div>

                <Timer
                    restTime={setData.restTime}
                    onUpdateTimer={(newTime) => onUpdateSet(exerciseId, setData.id, 'restTime', newTime)}
                />
            </div>
        </div>
    );
}
