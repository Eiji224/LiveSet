import '../bootstrap.js';
import React, {useState} from 'react';
import ReactDOM from 'react-dom/client';

import AddButtons from "./components/AddButtons.jsx";
import ProgramExercise from "./components/ProgramExercise.jsx";

const rootElement = document.getElementById('react-app');

if (rootElement) {
    const exercises = JSON.parse(rootElement.dataset.exercises)

    const root = ReactDOM.createRoot(rootElement);
    root.render(<TrainingProgram allExercises={exercises} />);
}

function TrainingProgram({ allExercises }) {
    const [programExercises, setProgramExercises] = useState([])
    const [programToEdit, setProgramToEdit] = useState(null);

    const addExercise = (exerciseData) => {
        const newProgramExercise = {
            id: Date.now(),
            title: exerciseData.title,
            exercise_id: exerciseData.id,
            sets: []
        };
        setProgramExercises([...programExercises, newProgramExercise]);
        setProgramToEdit(newProgramExercise);
    };

    const addSetToProgramExercise = () => {
        if (!programToEdit) return;

        setProgramExercises(programExercises.map(ex => {
            if (ex.id === programToEdit.id) {
                return {
                    ...ex,
                    sets:[...ex.sets, {
                        id: Date.now(),
                        weight: 0,
                        reps: 0,
                        restTime: 3 * 60 * 1000,
                    }]
                };
            }

            return ex;
        }))
    };

    return (
        <div className="w-full h-full">
            <div className="flex gap-4">
                <div className="flex flex-col w-1/2 gap-3">
                    {programExercises.map((ex, index) => (
                        <div
                            key={ex.id}
                            onClick={() => setProgramToEdit(ex)}
                            className={`cursor-pointer ${programToEdit?.id === ex.id ? 'border-2 border-sky-500 rounded-xl' : ''}`}
                        >
                            <ProgramExercise
                                data={ex}
                                order={index + 1}
                            />
                        </div>

                    ))}
                </div>

                <div className="w-1/4">
                    <AddButtons
                        exercises={allExercises}
                        onExerciseAdd={addExercise}
                        onAddSet={addSetToProgramExercise}
                    />
                </div>
            </div>
        </div>
    );
}
