import {useEffect, useRef} from "react";
import axios from "axios";


export default function useWebSocket(userId, roomId, updateCurrentState) {
    if (!roomId) return;

    const wsUrl = `${import.meta.env.VITE_WEBSOCKET_URL}/${roomId}`;

    const updateUpdateCurrentStateRef = useRef(updateCurrentState);

    useEffect(() => {
        updateUpdateCurrentStateRef.current = updateCurrentState;
    });

    useEffect(() => {
        const ws = new WebSocket(wsUrl);
        ws.onopen = () => console.log('ws connected')
        ws.onmessage = (e) => {
            const message = JSON.parse(e.data);

            if (message.senderId === userId) return;

            console.log(message);
            switch (message.type) {
                case 'update_state':
                    updateUpdateCurrentStateRef.current(message.payload);
                    break;
                case 'stop_training':
                    window.location.href="/trainings"
                    break;
            }
        }
        ws.onclose = () => setTimeout(connect, 2000)
        ws.onerror = (e) => console.error(e)

        return () => {
            if (ws) ws.close();
        }
    }, [roomId, wsUrl]);

    return (message) => {
        axios.post(`${import.meta.env.VITE_API_URL}/api/v1/ws/${roomId}`, message)
            .then(r => console.log(r.data))
            .catch(e => console.error(e));
    }
}
