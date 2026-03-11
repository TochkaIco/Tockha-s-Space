<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskImageController extends Controller
{
    public function destroy(Task $task)
    {
        Storage::disk('public')->delete($task->image_path);
        $task->update(['image_path' => null]);

        return back();
    }
}
