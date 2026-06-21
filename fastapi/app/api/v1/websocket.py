from fastapi import APIRouter, WebSocket, Request, Response
from json import dumps as json_dumps

from app.services import ws_manager, live_training_manager, write_log
from app.schemas import TrainingProgramSchema


router = APIRouter(
    prefix='/ws',
    tags=["WebSoscket"]
)


@router.websocket('/{room_id}')
async def websocket_connect(ws: WebSocket, room_id: str):
    await ws_manager.connect(ws, room_id)
    
    if room_id in live_training_manager.current_states:
        write_log("room_id found. sending current state")

        current_state = live_training_manager.current_states[room_id]
        payload = current_state.model_dump(by_alias=True)

        await ws.send_json({
            'type': 'update_state',
            'payload': payload
        })

    try:
        while True:
            await ws.receive_json()
    except:
        await ws_manager.disconnect(ws)

@router.post('/{room_id}')
async def websocket_broadcast(request: Request, room_id: str, response: Response):
    if room_id in ws_manager.active_rooms:
        data = await request.json()

        if data['type'] == 'update_state':
            write_log("Updating state...")
            training_state = TrainingProgramSchema.model_validate(data['payload'])
            await live_training_manager.update_state(room_id, training_state)

        await ws_manager.send_message(room_id, json_dumps(data))
        return {
            'status': 200,
            'body': {'ok': True},
            'errors': None
        }
    
    response.status_code = 404
    return {
        'status': 404,
        'body': None,
        'errors': 'Not found'
    }

@router.delete('/{room_id}')
async def end_training(room_id: str, response: Response):
    if room_id not in ws_manager.active_rooms:
        response.status_code = 404
        return {
            'status': 404,
            'body': None,
            'errors': 'Not found'
        }
    
    ws_message = {
        'type': 'stop_training'
    }
    await ws_manager.send_message(room_id, json_dumps(ws_message))
    
    await live_training_manager.end_training(room_id)

    response.status_code = 204