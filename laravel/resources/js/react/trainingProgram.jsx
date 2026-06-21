import '../bootstrap.js';
import React, {useEffect, useRef, useState} from 'react';
import ReactDOM from 'react-dom/client';

import ControlButtons from "./components/ControlButtons.jsx";
import ProgramExercise from "./components/ProgramExercise.jsx";
import Header from './components/Header.jsx';
import ProgramExerciseControl from './components/ProgramExerciseControl.jsx';

import useWebSocket from './hooks/useWebSocket.jsx';

const rootElement = document.getElementById('react-app');

if (rootElement) {
    const exercises = JSON.parse(rootElement.dataset.exercises);
    const userId = rootElement.dataset.userid;
    const trainingId = rootElement.dataset.trainingid;
    const liveTrainingId = rootElement.dataset.livetrainingid;

    let root = window.__reactRoot;
    if (!root) {
        root = ReactDOM.createRoot(rootElement);
        window.__reactRoot = root;
    }

    root.render(
        <TrainingProgram
            allExercises={exercises}
            userId={userId}
            trainingId={trainingId}
            liveTrainingId={liveTrainingId}
        />
    );
}

function TrainingProgram({ allExercises, userId, trainingId, liveTrainingId }) {
    const [isNewTraining, setIsNewTraining] = useState(true);
    const [isLiveTraining, setIsLiveTraining] = useState(liveTrainingId !== undefined)

    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");
    const [isPrivate, setIsPrivate] = useState(false)

    const [programExercises, setProgramExercises] = useState([])
    const [programToEdit, setProgramToEdit] = useState(null);

    const isIncomingUpdate = useRef(false);
    const isLoaded = useRef(false);


    const updateState = (body) => {
        setIsNewTraining(false);

        isIncomingUpdate.current = true;

        setTitle(body.title);
        setDescription(body.description);
        setIsPrivate(body.isPrivate);
        setProgramExercises(body.programExercises);

        isLoaded.current = true;
    }

    const getCurrentState = () => {
        const baseExerciseTrainingTime = 60;
        let trainingTime = 0;

        programExercises.forEach(exercise => {
            exercise.sets.forEach(set => {
                trainingTime += baseExerciseTrainingTime;
                trainingTime += set.restTime;
            })
        });

        return {
            title: title,
            description: description,
            isPrivate: isPrivate,
            userId: userId,
            trainingTime: trainingTime,
            programExercises: programExercises,
        };
    }

    const sendMessage = useWebSocket(userId, liveTrainingId, updateState);
    useEffect(() => {
        if (!isLiveTraining) return;
        if (!isLoaded.current) return;

        if (isIncomingUpdate.current) {
            isIncomingUpdate.current = false;
            return;
        }

        const currentState = getCurrentState();
        if (!currentState.title && currentState.programExercises.length === 0) return;

        const message = {
            type: 'update_state',
            senderId: userId,
            payload: currentState
        }
        sendMessage(message);
    }, [programExercises]);

    useEffect(() => {
        if (!trainingId || isLiveTraining) return;

        axios.get(`${import.meta.env.VITE_API_URL}/api/v1/trainings/${trainingId}`)
            .then(res => {
                const body = res.data.body;

                console.log(body);

                updateState(body);
            })
            .catch(err => console.error(err.response.data));
    }, [trainingId, isLiveTraining]);

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
                        timerStatus: "waiting",
                        timerUpdatedAt: new Date().toISOString(),
                    }]
                };
            }
            return ex;
        }))
    };

    const deleteSetFromProgramExercise = () => {
        const updated = programExercises.map(ex => {
            if (ex.id !== programToEdit.id) return ex;

            return {
                ...ex,
                sets: ex.sets.slice(0, -1)
            };
        });

        setProgramExercises(updated);
    }

    const updateProgramExercise = (programId, setId, key, value) => {
        setProgramExercises(prevPrograms => prevPrograms.map(program => {
            if (program.id !== programId) return program;

            return {
                ...program,
                sets: program.sets.map(set => {
                    if (set.id !== setId) return set;
                    if (key === 'timerStatus') return { ...set, [key]: value, timerUpdatedAt: new Date().toISOString() }

                    return { ...set, [key]: value };
                })
            };
        }));
    };

    const liftProgramExercise = () => {
        const index = programExercises.findIndex(ex => ex.id === programToEdit.id);
        if (index === -1 || index <= 0) return;

        const updatedExercises = [...programExercises];

        const temp = updatedExercises[index];
        updatedExercises[index] = updatedExercises[index - 1];
        updatedExercises[index - 1] = temp;

        setProgramExercises(updatedExercises);
    };

    const lowerProgramExercise = () => {
        const index = programExercises.findIndex(ex => ex.id === programToEdit.id);

        if (index === -1 || index === programExercises.length - 1) return;

        const updatedExercises = [...programExercises];

        const temp = updatedExercises[index];
        updatedExercises[index] = updatedExercises[index + 1];
        updatedExercises[index + 1] = temp;

        setProgramExercises(updatedExercises);
    };

    const deleteProgramExercise = () => {
        const index = programExercises.findIndex(ex => ex.id === programToEdit.id);
        if (index === -1) return;

        const updatedExercises = programExercises.filter(ex => ex.id !== programToEdit.id);

        if (updatedExercises.length === 0) {
            setProgramToEdit(null);
        } else if (index === 0) {
            setProgramToEdit(updatedExercises[0]);
        } else {
            setProgramToEdit(updatedExercises[index - 1]);
        }

        setProgramExercises(updatedExercises);
    };

    const saveState = () => {
        const training = getCurrentState();
        if (isNewTraining) {
            axios.post(`${import.meta.env.VITE_API_URL}/api/v1/trainings`, training)
                .then(() => window.location.href="/trainings")
                .catch(err => console.error(err.response.data));
        } else {
            axios.put(`${import.meta.env.VITE_API_URL}/api/v1/trainings/${trainingId}`, training)
                .then(() => console.log('Success!'))
                .catch(err => console.error(err.response.data))
        }
    };

    const deleteTraining = () => {
        axios.delete(`${import.meta.env.VITE_API_URL}/api/v1/trainings/${trainingId}`)
            .then(() => window.location.href="/trainings")
            .catch(err => console.error(err.response.data));
    };

    const stopLiveTraining = () => {
        axios.delete(`${import.meta.env.VITE_API_URL}/api/v1/ws/${liveTrainingId}`)
            .then(() => {
                saveState();
                window.location.href = "/trainings";
            })
            .catch(err => console.error(err.response.data));
    };

    return (
        <div className="w-full h-full">
            <Header
                isLiveTraining={isLiveTraining}
                title={title}
                setTitle={setTitle}
                description={description}
                setDescription={setDescription}
                isPrivate={isPrivate}
                setIsPrivate={setIsPrivate}
            />

            <div className="mt-5 flex gap-7 justify-center">
                <div className="sticky top-36 self-start">
                    <ProgramExerciseControl
                        onUp={liftProgramExercise}
                        onDown={lowerProgramExercise}
                        onDelete={deleteProgramExercise}
                    />
                </div>

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
                                onUpdateSet={updateProgramExercise}
                            />
                        </div>

                    ))}
                </div>

                <div className="sticky top-36 self-start w-1/10">
                    <ControlButtons
                        trainingId={trainingId}
                        exercises={allExercises}
                        isLiveTraining={isLiveTraining}
                        onExerciseAdd={addExercise}
                        onAddSet={addSetToProgramExercise}
                        onDeleteSet={deleteSetFromProgramExercise}
                        onSave={saveState}
                        onDeleteTraining={deleteTraining}
                        onEndLive={stopLiveTraining}
                    />
                </div>
            </div>
        </div>
    );
}
