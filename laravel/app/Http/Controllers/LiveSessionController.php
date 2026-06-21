<?php

namespace App\Http\Controllers;

use App\Enums\LiveSessionStatus;
use App\Enums\RedisMessageType;
use App\Models\Exercise;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class LiveSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $session = LiveSession::create([
            'unique_url' => Str::uuid(),
            'training_program_id' => $request->query('training_program_id'),
            'host_user_id' => auth()->id(),
            'status' => LiveSessionStatus::waiting
        ]);

        $redisMessage = [
            'type' => RedisMessageType::LIVE_SESSION_STARTED,
            'payload' => $session
        ];
        Redis::publish('liveset-events', json_encode($redisMessage));

        return redirect()->route('liveTrainings.show', $session->unique_url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uniqueUrl)
    {
        $session = LiveSession::where('unique_url', $uniqueUrl)->firstOrFail();

        abort_if($session->status === LiveSessionStatus::completed->value, 404);

        $session->status = LiveSessionStatus::in_progress;
        $session->save();

        $exercises = Exercise::all();
        return view('liveSession.show', compact(['session', 'exercises']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LiveSession $liveSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LiveSession $liveSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uniqueUrl)
    {
        //
    }
}
