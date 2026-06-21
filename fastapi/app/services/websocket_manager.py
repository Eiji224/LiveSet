from fastapi import WebSocket
from typing import List, Dict


class WebSocketManager:
    def __init__(self):
        self.active_rooms: Dict[str, List[WebSocket]] = {}
        self.subscriber_room: Dict[WebSocket: str] = {}
    

    async def connect(self, ws: WebSocket, room_id: str):
        await ws.accept()

        self.subscriber_room[ws] = room_id

        if room_id in self.active_rooms:
            self.active_rooms[room_id].append(ws)
        else:
            self.active_rooms[room_id] = [ws]

    async def disconnect(self, ws: WebSocket):
        if ws in self.subscriber_room:
            room_id = self.subscriber_room.pop(ws)
            self.active_rooms[room_id].remove(ws)

    async def send_message(self, room_id: str, message: str):
        if room_id not in self.active_rooms:
            return
        
        dead = []
        subscribers = self.active_rooms[room_id]

        for ws in subscribers:
            try:
                await ws.send_text(message)
            except:
                dead.append(ws)
        
        for ws in dead:
            await self.disconnect(ws)


ws_manager = WebSocketManager()