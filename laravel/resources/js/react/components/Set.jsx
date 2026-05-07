import { useState } from "react";

export default function Set({ setData, index }) {
    const [weight, setWeight] = useState(setData.weight);
    const [reps, setReps] = useState(setData.reps);
    const [timer, setTimer] = useState(setData.restTime);

    return (
        <div className="flex flex-row justify-center items-center w-full gap-3 p-4">
            <span>{index}</span>
            <div className="flex flex-col flex-1">
                <div className="flex justify-between px-10">
                    <div>
                        <label>Вес: </label>
                        <input
                            type="number"
                            value={weight}
                            onChange={e => setWeight(Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16"
                        /> кг
                    </div>

                    <div>
                        <label>Подходы: </label>
                        <input
                            type="number"
                            value={reps}
                            onChange={e => setReps(Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16" />
                    </div>
                </div>
                <div className="bg-sky-500 rounded-xl w-full">
                    <p className="text-center text-white">{timer / (60 * 1000)}:{timer % (60 * 1000)}</p>
                </div>
            </div>
        </div>
    );
}
