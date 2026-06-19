import { useState } from "react";
import Modal from "./Modal.jsx";

// add exercises and add sets
// modal for choosing exercise from the list of one
export default function ControlButtons({ trainingId, exercises, onExerciseAdd, onAddSet, onDeleteSet, onSave, onDeleteTraining }) {
    const [isAddOpen, setIsAddOpen] = useState(false);
    const [exerciseToAdd, setExerciseToAdd] = useState(null);
    const exercisesArr = Object.values(exercises);

    const closeModal = (isAdd) => {
        setIsAddOpen(false)

        if (isAdd) {
            onExerciseAdd(exerciseToAdd)
        }

        setExerciseToAdd(null);
    }

    const deleteButton = <button
        onClick={() => onDeleteTraining()}
        className="mt-5 text-white px-5 py-2 bg-red-500 rounded-xl hover:bg-red-700 hover:cursor-pointer transition-all"
    >
        Удалить тренировку
    </button>

    return (
        <div className="flex flex-col gap-3 bg-white p-3 rounded-xl">
            <button
                onClick={() => setIsAddOpen(true)}
                className="text-white px-5 py-2 bg-sky-500 rounded-xl hover:bg-sky-700 hover:cursor-pointer transition-all"
            >
                Добавить упражнение
            </button>
            <div className="flex flex-col my-5 gap-3">
                <button
                    onClick={() => onAddSet()}
                    className="text-white px-5 py-2 bg-sky-500 rounded-xl hover:bg-sky-700 hover:cursor-pointer transition-all"
                >
                    Добавить подход
                </button>
                <button
                    onClick={() => onDeleteSet()}
                    className="text-white px-5 py-2 bg-red-500 rounded-xl hover:bg-red-700 hover:cursor-pointer transition-all"
                >
                    Удалить последний подход
                </button>
            </div>

            <button
                onClick={() => onSave()}
                className="mt-5 text-white px-5 py-2 bg-emerald-500 rounded-xl hover:bg-emerald-700 hover:cursor-pointer transition-all"
            >
                Сохранить тренировку
            </button>

            {trainingId && deleteButton}

            <Modal isOpen={isAddOpen}>
                <div className="flex flex-col gap-3">
                    <h2 className="text-center text-2xl">Добавить упражнение</h2>

                    <div className="grid grid-cols-3 gap-3">
                        {exercisesArr.map(exercise => (
                            <div
                                key={exercise.id}
                                className={`p-2 border rounded-xl hover:cursor-pointer ${exercise === exerciseToAdd ? 'border-2 border-emerald-500 hover:border-emerald-500' : 'border-gray-200 hover:border-gray-400'}`}
                                onClick={() => setExerciseToAdd(exercise)}
                            >
                                <p className="text-lg">{exercise.title}</p>
                                <span className="text-gray-400">{exercise.description}</span>
                            </div>
                        ))}
                    </div>
                </div>

                <div className="flex flex-row justify-end gap-2 mt-5">
                    <button
                        className="text-gray-700 px-5 py-2 border-gray-700 border rounded-xl hover:bg-gray-300 hover:text-black hover:cursor-pointer transition-all"
                        onClick={() => closeModal(false)}
                    >
                        Отмена
                    </button>
                    <button
                        className="text-white px-5 py-2 bg-emerald-500 rounded-xl hover:bg-emerald-700 hover:cursor-pointer transition-all"
                        onClick={() => closeModal(true)}
                    >
                        Добавить
                    </button>
                </div>
            </Modal>
        </div>
    );
}
