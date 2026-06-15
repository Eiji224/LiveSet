import React, { useState, useEffect, useRef } from "react";

export default function Timer({ restTime }) {
    const [initialSeconds, setInitialSeconds] = useState(restTime ? restTime : 150);
    const [timeLeft, setTimeLeft] = useState(restTime ? restTime : 150);

    const [inputMin, setInputMin] = useState(restTime ? Math.floor(restTime / 60) : 2);
    const [inputSec, setInputSec] = useState(restTime ? restTime % 60 : 30);

    const [isRunning, setIsRunning] = useState(false);
    const timerRef = useRef(null);
    const progress = initialSeconds > 0 ? (timeLeft / initialSeconds) * 100 : 0;

    useEffect(() => {
        if (isRunning && timeLeft > 0) {
            timerRef.current = setInterval(() => {
                setTimeLeft((prev) => prev - 1);
            }, 1000);
        } else if (timeLeft === 0) {
            setIsRunning(false);
            clearInterval(timerRef.current);
        }

        return () => clearInterval(timerRef.current);
    }, [isRunning, timeLeft]);

    const saveTimer = (minutes, seconds) => {
        const totalSecs = parseInt(minutes, 10) * 60 + parseInt(seconds, 10);
        if (totalSecs > 0) {
            setInitialSeconds(totalSecs);
            setTimeLeft(totalSecs);
        }
    }

    const formatTime = (time) => {
        const mins = Math.floor(time / 60);
        const secs = time % 60;
        return `${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    };

    return (
        <div className="flex flex-col gap-1">
            <div className="flex flex-col items-center">
                <p className="text-center">Таймер отдыха</p>
                <div className="flex gap-1 text-center">
                    <input
                        type="number"
                        min="0"
                        max="59"
                        className="border rounded-lg px-2 py-1 max-w-16"
                        value={inputMin}
                        onChange={e => {
                            setInputMin(e.target.value);
                            saveTimer(e.target.value, inputSec);
                        }}
                        disabled={isRunning}
                    />
                    <span className="text-center">:</span>
                    <input
                        type="number"
                        min="-1"
                        max="60"
                        className="border rounded-lg px-2 py-1 max-w-16"
                        value={inputSec}
                        onChange={e => {
                            if (e.target.value == 60) {
                                setInputSec(0);
                                setInputMin(Number(inputMin) + 1);
                            } else if (e.target.value == -1) {
                                setInputSec(59);
                                setInputMin(Number(inputMin) - 1);
                            } else {
                                setInputSec(e.target.value);
                            }
                            saveTimer(inputMin, e.target.value);
                        }}
                        disabled={isRunning}
                    />
                </div>
            </div>
            <div className="relative w-full h-16 bg-slate-100 rounded-xl overflow-hidden">
                <div 
                    className="absolute top-0 left-0 h-full bg-sky-500" 
                    style={{ width: `${progress}%`, transition: 'width 1s linear' }}
                />
                <div className="absolute inset-0 z-10 flex items-center justify-between px-4">
                    <span className="text-xl font-bold stext-slate-800 drop-shadow-sm">
                        {formatTime(timeLeft)}
                    </span>
                    <div className="flex gap-3">
                        <button 
                            onClick={() => setIsRunning(!isRunning)}
                            className="w-10 h-10 bg-white/80 hover:bg-white rounded-lg text-sm font-medium transition-colors"
                        >
                            { isRunning ? <img className="w-full h-full" src="/icons/pause.svg" alt="Пауза" /> : <img className="w-full h-full" src="/icons/continue.svg" alt="Продолжить" /> }
                        </button>
                        <button 
                            onClick={() => { setIsRunning(false); setTimeLeft(initialSeconds); }}
                            className="w-10 h-10 bg-red-600 hover:bg-red-600/80 rounded-lg text-sm font-medium transition-colors"
                        >
                            <img className="w-full h-full" src="/icons/stop.svg" alt="Сброс" />
                        </button>
                    </div>
                </div>

            </div>
        </div>
    )
}