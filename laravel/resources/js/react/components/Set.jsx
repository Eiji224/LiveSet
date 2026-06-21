import { useEffect, useState } from "react";
import Timer from "./Timer";

export default function Set({ setData, index, exerciseId, onUpdateSet }) {
    const weight = setData.weight ?? 1;
    const reps = setData.reps ?? 1;
    const timerStatus = setData.timerStatus;
    const timerUpdatedAt = setData.timerUpdatedAt;

    return (
        <div className="flex flex-row justify-center items-center w-full gap-3 p-4 border border-gray-200 rounded-xl">
            <span>{index}</span>
            <div className="flex flex-col gap-3 flex-1">
                <div className="flex justify-between px-10">
                    <div>
                        <label>Вес: </label>
                        <input
                            type="number"
                            value={weight}
                            min="1"
                            onChange={e => onUpdateSet(exerciseId, setData.id, 'weight', Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16"
                        /> кг
                    </div>

                    <div>
                        <label>Повторения: </label>
                        <input
                            type="number"
                            value={reps}
                            min="1"
                            onChange={e => onUpdateSet(exerciseId, setData.id, 'reps', Number(e.target.value))}
                            className="border rounded-lg px-2 py-1 max-w-16" />
                    </div>
                </div>

                <Timer
                    restTime={setData.restTime}
                    timerStatus={timerStatus}
                    timerUpdatedAt={timerUpdatedAt}
                    onUpdateTimer={(newTime) => onUpdateSet(exerciseId, setData.id, 'restTime', newTime)}
                    onUpdateStatus={(newStatus) => onUpdateSet(exerciseId, setData.id, 'timerStatus', newStatus)}
                />
            </div>
        </div>
    );
}
