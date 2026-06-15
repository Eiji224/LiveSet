import '../bootstrap.js';
import React, {useState} from 'react';
import ReactDOM from 'react-dom/client';

import ControlButtons from "./components/ControlButtons.jsx";
import ProgramExercise from "./components/ProgramExercise.jsx";
import Header from './components/Header.jsx';

const rootElement = document.getElementById('react-app');

if (rootElement) {
    const exercises = JSON.parse(rootElement.dataset.exercises)

    const root = ReactDOM.createRoot(rootElement);
    root.render(<TrainingProgram allExercises={exercises} />);
}

function TrainingProgram({ allExercises }) {
    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");
    const [isPrivate, setIsPrivate] = useState(false)

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
                        weight: 5,
                        reps: 10,
                        restTime: 150,
                    }]
                };
            }
            return ex;
        }))
    };

    const saveState = () => {
        const baseExerciseTrainingTime = 60;
        let trainingTime = 0;

        programExercises.forEach(exercise => {
            exercise.sets.forEach(set => {
                trainingTime += baseExerciseTrainingTime;
                trainingTime += set.restTime;
            })
        })

        const training = {
            title: title,
            description: description,
            isPrivate: isPrivate,
            trainingTime: trainingTime,
            programExercises: programExercises,
        };

        console.log(training)

        axios.post('/api/training-programs', training)
            .then(res => console.log('Success!'))
            .catch(err => console.error(err.response.data));
    };

    return (
        <div className="w-full h-full">
            <Header
                title={title}
                setTitle={setTitle}
                description={description}
                setDescription={setDescription}
                isPrivate={isPrivate}
                setIsPrivate={setIsPrivate}
            />

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
                    <ControlButtons
                        exercises={allExercises}
                        onExerciseAdd={addExercise}
                        onAddSet={addSetToProgramExercise}
                        onSave={saveState}
                    />
                </div>
            </div>
        </div>
    );
}
