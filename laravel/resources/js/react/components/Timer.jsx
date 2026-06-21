import React, { useState, useEffect, useRef } from "react";

export default function Timer({ restTime, timerStatus, timerUpdatedAt, onUpdateTimer, onUpdateStatus }) {
    const duration = restTime ? restTime : 150;

    const [timeLeft, setTimeLeft] = useState(duration);
    const [isRunning, setIsRunning] = useState(false);

    const timerRef = useRef(null);

    const displayMin = Math.floor(duration / 60);
    const displaySec = duration % 60;

    useEffect(() => {
        if (timerStatus === 'running') {
            setIsRunning(true);

            if (timeLeft === duration) {
                const numUpdatedAt = new Date(timerUpdatedAt).getTime();
                const timePassed = Math.floor((Date.now() - numUpdatedAt) / 1000);
                const remaining = restTime - timePassed;
                setTimeLeft(remaining);
            }
        } else if (timerStatus === 'waiting') {
            setIsRunning(false);
            setTimeLeft(duration);
        } else {
            setIsRunning(false);
        }
    }, [duration, timerStatus, timerUpdatedAt]);

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

    const handleInputChange = (newMin, newSec) => {
        const mins = Math.max(0, parseInt(newMin, 10) || 0);
        const secs = parseInt(newSec, 10) || 0;

        let totalSecs = mins * 60 + secs;

        if (secs === 60) {
            totalSecs = (mins + 1) * 60;
        } else if (secs === -1) {
            totalSecs = Math.max(0, (mins - 1) * 60 + 59);
        }

        if (totalSecs >= 0) {
            onUpdateTimer(totalSecs);
        }
    };

    const progress = duration > 0 ? (timeLeft / duration) * 100 : 0;

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
                        className="border rounded-lg px-2 py-1 max-w-16"
                        value={displayMin}
                        onChange={e => handleInputChange(e.target.value, displaySec)}
                        disabled={isRunning}
                    />
                    <span className="text-center">:</span>
                    <input
                        type="number"
                        min="-1"
                        max="60"
                        className="border rounded-lg px-2 py-1 max-w-16"
                        value={displaySec}
                        onChange={e => handleInputChange(displayMin, e.target.value)}
                        disabled={isRunning}
                    />
                </div>
            </div>

            <div className="relative w-full h-16 bg-slate-100 rounded-xl overflow-hidden">
                <div
                    className="absolute top-0 left-0 h-full bg-sky-500"
                    style={{ width: `${progress}%`, transition: isRunning ? 'width 1s linear' : 'none' }}
                />
                <div className="absolute inset-0 z-10 flex items-center justify-between px-4">
                    <span className="text-xl font-bold text-slate-800 drop-shadow-sm">
                        {formatTime(timeLeft)}
                    </span>
                    <div className="flex gap-3">
                        <button
                            onClick={() => {
                                isRunning ? onUpdateStatus('paused') : onUpdateStatus('running');
                                setIsRunning(!isRunning);
                            }}
                            className="w-10 h-10 bg-white/80 hover:bg-white rounded-lg text-sm font-medium transition-colors"
                        >
                            { isRunning ? <img className="w-full h-full" src="/icons/pause.svg" alt="Пауза" /> : <img className="w-full h-full" src="/icons/continue.svg" alt="Продолжить" /> }
                        </button>
                        <button
                            onClick={() => {
                                setIsRunning(false);
                                setTimeLeft(duration);
                                onUpdateStatus('waiting')
                            }}
                            className="w-10 h-10 bg-red-600 hover:bg-red-600/80 rounded-lg text-sm font-medium transition-colors"
                        >
                            <img className="w-full h-full" src="/icons/stop.svg" alt="Сброс" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
