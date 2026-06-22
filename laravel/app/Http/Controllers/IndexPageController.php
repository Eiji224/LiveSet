<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\LiveSession;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class IndexPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $exercisesQty = Exercise::count();
        $trainingsQty = TrainingProgram::count();
        $liveSessionsQty = LiveSession::count();

        return view('welcome', compact(['exercisesQty', 'trainingsQty', 'liveSessionsQty']));
    }
}
