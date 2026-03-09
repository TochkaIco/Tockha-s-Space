<?php

namespace App\Http\Controllers;

use App\Models\TaskStep;

class StepController extends Controller
{
    //
    public function update(TaskStep $step)
    {

        $step->update(['completed' => ! $step->completed]);

        return back();
    }
}
